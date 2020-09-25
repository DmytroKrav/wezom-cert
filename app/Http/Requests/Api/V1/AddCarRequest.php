<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\VinCode;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddCarRequest extends FormRequest
{
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

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
