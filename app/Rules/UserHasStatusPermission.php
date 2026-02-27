<?php

namespace App\Rules;

use App\Models\Barcode;
use App\Models\Permission;
use Illuminate\Contracts\Validation\Rule;

class UserHasStatusPermission implements Rule
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
        $statusManager = app(\App\Services\BarcodeStatusManager::class);
        $availableStatuses = $statusManager->getAvailableStatusesForUser(\Auth::user());

        return array_key_exists($value, $availableStatuses);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Seçmiş olduğunuz statü için yetkiniz bulunmamaktadır.';
    }
}
