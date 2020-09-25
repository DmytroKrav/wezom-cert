<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddCarRequest;
use App\Http\Resources\Api\V1\CarResource;
use App\Models\StolenCar;

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

    public function store(AddCarRequest $request)
    {
        return CarResource::make($this->model->createNewRecord($request));
    }
}
