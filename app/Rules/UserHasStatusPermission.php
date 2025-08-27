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
        /** @var \Illuminate\Support\Collection $permissions */
        $permissions = \Auth::user()->permissions;

        $statuses = Barcode::STATUSES;

        /**
         * Laborartuvar işlemleri yetkisine sahip değilse
         */
        if ($permissions->where('id', Permission::LAB_PROCESSES)->isEmpty()) {
            unset($statuses[Barcode::STATUS_CONTROL_REPEAT], $statuses[Barcode::STATUS_PRE_APPROVED], $statuses[Barcode::STATUS_REJECTED]);
        }

        /**
         * Yönetim işlemleri yetkisine sahip değilse
         */
        if ($permissions->where('id', Permission::MANAGEMENT)->isEmpty()) {
            unset($statuses[Barcode::STATUS_SHIPMENT_APPROVED]);
        }


        /**
         * Müşteri transfer işlemleri yetkisine sahip değilse
         */
        if ($permissions->where('id', Permission::CUSTOMER_TRANSFER)->isEmpty()) {
            unset($statuses[Barcode::STATUS_CUSTOMER_TRANSFER], $statuses[Barcode::STATUS_DELIVERED]);
        }

        return array_key_exists($value, $statuses);
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
