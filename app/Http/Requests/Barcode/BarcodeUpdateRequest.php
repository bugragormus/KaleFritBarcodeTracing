<?php

namespace App\Http\Requests\Barcode;

use App\Models\Barcode;
use App\Models\Company;
use App\Models\Warehouse;
use App\Rules\UserHasLabNotePermission;
use App\Rules\UserHasStatusPermission;
use App\Rules\UserHasWarehousePermission;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BarcodeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $barcode = Barcode::find($this->route('barcode'));
        
        return [
            'status' => [
                'nullable',
                'integer',
                new UserHasStatusPermission(),
                function ($attribute, $value, $fail) use ($barcode) {
                    if ($barcode && $value && $value != $barcode->status && !$barcode->canTransitionTo($value)) {
                        $currentStatus = Barcode::STATUSES[$barcode->status] ?? 'Bilinmiyor';
                        $newStatus = Barcode::STATUSES[$value] ?? 'Bilinmiyor';
                        $fail("Geçersiz durum geçişi: {$currentStatus} durumundan {$newStatus} durumuna geçiş yapılamaz.");
                    }
                }
            ],
            // transfer_status artık kullanılmıyor - sadece ana durum kullanılıyor
            'note' => [
                'nullable',
                'string'
            ],
            'warehouse_id' => [
                'required_if:status,'. Barcode::STATUS_SHIPMENT_APPROVED,
                'nullable',
                Rule::exists(Warehouse::class, 'id'),
                new UserHasWarehousePermission()
            ],
            'company_id' => [
                'nullable',
                Rule::exists(Company::class, 'id')
            ],
            'lab_note' => [
                'nullable',
                new UserHasLabNotePermission()
            ],
            'rejection_reasons' => [
                'required_if:status,' . Barcode::STATUS_REJECTED,
                'array'
            ],
            'rejection_reasons.*' => [
                'exists:rejection_reasons,id'
            ]
        ];
    }
}
