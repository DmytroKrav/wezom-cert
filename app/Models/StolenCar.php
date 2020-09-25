<?php

namespace App\Models;

use App\Http\Requests\Api\V1\AddCarRequest;
use App\Http\Services\VpicService;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Sortable;

class StolenCar extends Model
{
    use Sortable;

    public $defaultLimit = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_name', 'maker_id', 'model_id', 'color_hex', 'year', 'gov_number', 'vin_code'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function model()
    {
        return $this->belongsTo(CarModel::class);
    }

    /**
     * @param AddCarRequest $request
     * @return mixed
     */
    public function createNewRecord(AddCarRequest $request)
    {
        $vpicService = new VpicService();
        $vinCode = $request->get('vin_code');
        $decodedVin = $vpicService->decodeVinCode($vinCode);
        if (!$decodedVin) {
            return response()->json(['message' => 'Vin code dosen`t exist']);
        }
        $carAdditionalData = [];

        if ($dataItems = optional($decodedVin)->Results) {
            foreach ($dataItems as $item) {
                if ($item->Variable == 'Make') {
                    $carAdditionalData['maker_id'] = $item->ValueId;
                }
                if ($item->Variable == 'Model') {
                    $carAdditionalData['model_id'] = $item->ValueId;
                }
                if ($item->Variable == 'Model Year') {
                    $carAdditionalData['year'] = $item->Value;
                }
            }
        }
        $data = array_merge($request->only(['vin_code', 'user_name', 'color_hex', 'gov_number']), $carAdditionalData);

        return StolenCar::create($data);
    }
}
