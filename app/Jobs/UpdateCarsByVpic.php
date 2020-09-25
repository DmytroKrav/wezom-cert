<?php

namespace App\Jobs;

use App\Http\Services\VpicService;
use App\Models\CarMaker;
use App\Models\CarModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCarsByVpic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string
     */
    private $carsByMakerLink = 'https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeId/:makerId?format=json';

    /**
     * @var string
     */
    private $makersLink = 'https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json';

    private $service;

    public function __construct(VpicService $service)
    {
        $this->service = $service;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle()
    {
        $this->updateMakers();
        $this->updateMakersCars();
    }

    private function updateMakers()
    {
        try {
            $makersResponse = $this->service->sendSimpleGetRequestToAddress($this->makersLink);
            if ($makersResponse) {
                $results = json_decode($makersResponse->getBody()->getContents())->Results;
                foreach ($results as $item) {
                    $dirtyMakers = [];
                    $currentMakers = CarMaker::all();
                    $existedMaker = $currentMakers->first(function (CarMaker $maker) use ($item) {
                        return $maker->external_id == $item->Make_ID;
                    });

                    if ($existedMaker == null) {
                        $dirtyMakers[] = [
                            'external_id' => $item->Make_ID,
                            'name' => $item->Make_Name,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                CarMaker::insert($dirtyMakers);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function updateMakersCars()
    {
        $carsMakers = CarMaker::all();
        $allCarsModels = CarModel::all();
        try {
            foreach ($carsMakers as $maker) {
                $link = str_replace(':makerId', $maker->external_id, $this->makersLink);
                $makersResponse = $this->service->sendSimpleGetRequestToAddress($link);

                if ($makersResponse) {
                    $dirtyCarsModels = [];
                    $currentMakersCarsModels = $allCarsModels->filter(function (CarModel $model) use ($maker) {
                        return $model->maker_id = $maker->id;
                    });

                    $results = json_decode($makersResponse->getBody()->getContents())->Results;

                    foreach ($results as $item) {
                        $existedCarModel = $currentMakersCarsModels->first(function (CarModel $carModel) use ($item) {
                            return $carModel->external_id == $item->Model_ID;
                        });

                        if ($existedCarModel == null) {
                            $dirtyCarsModels[] = [
                                'external_id' => $item->Model_ID,
                                'name' => $item->Model_Name,
                                'maker_id' => $item->Make_ID,
                                'created_at' => now(),
                                'updated_at' => now()
                            ];
                        }
                    }

                    CarModel::insert($dirtyCarsModels);
                }
            }

        } catch (\Throwable $e) {
            report($e);
        }
    }
}
