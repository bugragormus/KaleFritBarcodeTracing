<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CheckIsExistsBarcodeId implements Rule
{
    /**
     * @var int
     */
    public $barcodeId;

    /**
     * Create a new rule instance.
     *
     * @param int|null $barcodeId
     */
    public function __construct(?int $barcodeId = null)
    {
        $this->barcodeId = $barcodeId;
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
        // barcodeId null ise geçerli kabul et
        if ($this->barcodeId === null) {
            return true;
        }
        
        return !in_array((string)$this->barcodeId, $value, true);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Birleştirilecek barkodu burada seçmeyiniz.';
    }
}
