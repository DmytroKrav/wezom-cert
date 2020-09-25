<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCarRequest;
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

    public function index(Request $request)
    {
        $result = $this->model::query();

        if (method_exists($this->model, 'scopeSort')) {
            $result = $result->sort($request->all());
        }

        $result->orderByDesc('id');

        $result = $result->paginate($this->getLimit($request))->appends($request->query());

        return new StolenCarsResource($result);
    }

    public function store(AddCarRequest $request)
    {
        return CarResource::make($this->model->createNewRecord($request));
    }

    /**
     * @param  Request  $request
     * @return int
     */
    protected function getLimit(Request $request): int
    {
        if ((int) $request->get('per_page') > 0) {
            return (int) $request->get('per_page');
        }

        return $this->model->defaultLimit;
    }
}
