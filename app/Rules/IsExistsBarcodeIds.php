<?php

namespace App\Rules;

use App\Models\Barcode;
use Illuminate\Contracts\Validation\Rule;

class IsExistsBarcodeIds implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $barcodeCount = Barcode::whereIn('id', $value)->count();

        return $barcodeCount === count($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Barkodları kontrol ediniz.';
    }
}
