<?php

namespace App\Rules;

use App\Http\Services\VpicService;
use Illuminate\Contracts\Validation\Rule;

class VinCode implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $vinDecode = (new VpicService())->decodeVinCode($value);

        return $vinDecode ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Vin code is invalid';
    }
}
