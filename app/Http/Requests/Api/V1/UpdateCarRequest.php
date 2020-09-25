<?php

namespace App\Http\Requests\Api\V1;

use App\Models\StolenCar;
use App\Rules\VinCode;
use App\Traits\BreakAutoValidationRedirect;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCarRequest extends FormRequest
{
    use BreakAutoValidationRedirect;

    public function rules(): array
    {
        return [
            'user_name' => 'string|max:255',
            'gov_number' => 'string|max:10',
            'color_hex' => 'string|max:10',
            'vin_code' => ['string', 'max:255', 'unique:stolen_cars,vin_code', new VinCode()], //TODO decide  answer
        ];
    }

    public function withValidator($validator)
    {
        $id = $this->route('id');

        if(!$id || !StolenCar::whereId($id)->exists()) {
            $validator->errors()->add('id', 'This record isn exists');
        }
    }
}
