<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\StolenCarExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCarRequest;
use App\Http\Requests\Api\V1\UpdateCarRequest;
use App\Http\Resources\Api\V1\CarResource;
use App\Http\Resources\Api\V1\StolenCarsResource;
use App\Models\StolenCar;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

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
        $result = $this->getIndexQuery($request)->paginate($this->getLimit($request))->appends($request->query());

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
        StolenCar::find($request->get('id'))
            ->update($request->only('color_hex', 'gov_number', 'vin_code', 'user_name'));

        return $this->successResponse();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        StolenCar::find($request->get('id'))->delete();

        return $this->successResponse();
    }

    public function exportWithFilters(Request $request)
    {
        $data = $this->getIndexQuery($request)->get();

        $filename = time() . 'cars.xls';
        Excel::store(new StolenCarExport($data), $filename);

        return $this->successResponse(['file_path' => 'path to file in storage']);
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

    private function getIndexQuery(Request $request)
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

        return $result;
    }

    /**
     * @var $data array
     * @return \Illuminate\Http\JsonResponse
     */
    private function successResponse(array $data = [])
    {
        return response()->json(array_merge(['success' => true], $data));
    }
}
