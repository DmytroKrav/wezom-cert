<?php

namespace App\Http\Requests\Api\V1;

use App\Models\StolenCar;
use App\Rules\VinCode;
use App\Traits\BreakAutoValidationRedirect;
use Illuminate\Foundation\Http\FormRequest;

class DeleteCarRequest extends FormRequest
{
    use BreakAutoValidationRedirect;

    public function rules()
    {
        return [];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this->route('stolen_car');

            if (!$id || !StolenCar::whereId($id)->exists()) {
                $validator->errors()->add('id', 'This record isn exists');
            }
        });
    }
}
