<?php

namespace App\Http\Requests;

use App\Models\Barcode;
use App\Models\Warehouse;
use App\Models\Kiln;
use App\Rules\CheckIsExistsBarcodeId;
use App\Rules\IsExistsBarcodeIds;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BarcodeMergeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'barcode_ids' => [
                'nullable',
                'array',
                new IsExistsBarcodeIds(),
            ],
            'barcode_ids_2' => [
                'nullable',
                'array',
                new IsExistsBarcodeIds(),
            ],
            'warehouse_id' => [
                'required',
                Rule::exists(Warehouse::class, 'id')
            ],
            'party_number' => [
                'required',
            ],
            'note' => [
                'nullable',
            ]
        ];

        // En az bir grupta barkod seçilmiş olmalı
        $rules['barcode_ids'][] = function ($attribute, $value, $fail) {
            if (empty($this->barcode_ids) && empty($this->barcode_ids_2)) {
                $fail('En az bir grupta barkod seçilmelidir.');
            }
        };

        return $rules;
    }
}
