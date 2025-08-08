<?php

namespace App\Rules;

use App\Models\Barcode;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CheckQuantity implements Rule
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
        
        $barcode =  \DB::select('
            SELECT sum(q1.quantity) as quantity from barcodes
            left join quantities q1 on (q1.id = barcodes.quantity_id)
            where barcodes.id in (' . implode(',',  array_merge($value, [$this->barcodeId])) . ')');

        return ($barcode[0]->quantity % 1000) === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Miktarlar toplamı 1000in katları olmalıdır.';
    }
}
