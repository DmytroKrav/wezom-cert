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
            'vin_code' => ['string', 'max:255', new VinCode()], //TODO decide  answer
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $id = $this->route('id');
            $stolenCar = StolenCar::find($id);
            $stolenCarByVin = StolenCar::where('vin_code', $this->request->get('vin_code'))->first();

            if (!$id || !$stolenCar) {
                $validator->errors()->add('id', 'This record isn exists');
            }

            if ($stolenCarByVin && $stolenCarByVin->id !== $id) {
                $validator->errors()->add('id', 'This vin_code is already exists');
            }
        });
    }
}
