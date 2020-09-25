<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\VinCode;
use App\Traits\BreakAutoValidationRedirect;
use Illuminate\Foundation\Http\FormRequest;

class AddCarRequest extends FormRequest
{
    use BreakAutoValidationRedirect;
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'user_name' => 'required|string|max:255',
            'gov_number' => 'required|string|max:10',
            'color_hex' => 'required|string|max:10',
            'vin_code' => ['required', 'string', 'max:255', 'unique:stolen_cars,vin_code', new VinCode()], //TODO decide  answer
        ];
    }
}
