<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCarRequest;
use App\Http\Requests\Api\V1\DeleteCarRequest;
use App\Http\Requests\Api\V1\UpdateCarRequest;
use App\Http\Resources\Api\V1\CarResource;
use App\Http\Resources\Api\V1\StolenCarsResource;
use App\Models\StolenCar;
use Illuminate\Http\Request;

class StolenCarController extends Controller
{
    /**
     * @var StolenCar
     */
    private $model;

    public function __construct(StolenCar $model)
    {
        $this->model = $model;
    }

    /**
     * @param Request $request
     * @return StolenCarsResource
     */
    public function index(Request $request)
    {
        $result = $this->model::query();

        if (method_exists($this->model, 'scopeSort')) {
            $result = $result->sort($request->all());
        }

        if ($filter = $request->get('filter')) {
            $result->where(function ($builder) use ($filter) {
                $builder->where('gov_number', 'LIKE', "%{$filter}%")
                    ->orWhere('user_name', 'LIKE', "%{$filter}%")
                    ->orWhere('vin_code', 'LIKE', "%{$filter}%");
            });
        }

        $result->orderByDesc('id');

        $result = $result->paginate($this->getLimit($request))->appends($request->query());

        return new StolenCarsResource($result);
    }

    /**
     * @param AddCarRequest $request
     * @return CarResource
     */
    public function store(AddCarRequest $request)
    {
        return CarResource::make($this->model->createNewRecord($request));
    }

    /**
     * @param UpdateCarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateCarRequest $request)
    {
        $car = StolenCar::find($request->route('stolen_car'))
            ->update($request->only('color_hex', 'gov_number', 'vin_code', 'user_name'));

        return CarResource::make($car);
    }

    /**
     * @param DeleteCarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteCarRequest $request)
    {
        StolenCar::find($request->route('stolen_car'))->delete();

        return response()->json(['success' => true]);
    }

    /**
     * @param  Request  $request
     * @return int
     */
    private function getLimit(Request $request): int
    {
        if ((int) $request->get('per_page') > 0) {
            return (int) $request->get('per_page');
        }

        return $this->model->defaultLimit;
    }
}
