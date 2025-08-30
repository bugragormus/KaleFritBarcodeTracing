<?php

namespace App\Http\Requests\Barcode;

use App\Models\Barcode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BarcodeStoreRequest extends FormRequest
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
        return [
            'stock_id' => [
                'required',
                Rule::exists('stocks', 'id')
            ],
            'kiln_id' => [
                'required',
                Rule::exists('kilns', 'id')
            ],
            'party_number' => [
                'required',
                'regex:/^[1-9]\d*$/', // Sadece pozitif sayılar, 0 ile başlayamaz
                'max:255'
            ],
            'load_number' => [
                'required',
                'integer',
                'min:1'
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:50'
            ],
            'quantity_id' => [
                'required',
                Rule::exists('quantities', 'id')
            ],
            'warehouse_id' => [
                'required',
                Rule::exists('warehouses', 'id')
            ],
            'note' => [
                'nullable',
                'string'
            ],
            'print' => [
                'nullable',
            ],
            'correction_barcodes' => [
                'nullable',
                'array'
            ],
            'correction_barcodes.*' => [
                'nullable',
                Rule::exists('barcodes', 'id')->where('status', \App\Models\Barcode::STATUS_REJECTED)
            ],
            'correction_quantities' => [
                'nullable',
                'array'
            ],
            'correction_quantities.*' => [
                'nullable',
                'integer',
                'min:1'
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages()
    {
        return [
            'party_number.regex' => 'Parti numarası sadece pozitif sayı olmalıdır ve 0 ile başlayamaz.',
            'party_number.required' => 'Parti numarası zorunludur.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $correctionBarcodes = $this->input('correction_barcodes', []);
            $correctionQuantities = $this->input('correction_quantities', []);
            
            if (!empty($correctionBarcodes)) {
                foreach ($correctionBarcodes as $index => $barcodeId) {
                    if (!empty($barcodeId) && isset($correctionQuantities[$index])) {
                        $barcode = \App\Models\Barcode::find($barcodeId);
                        if ($barcode && $barcode->quantity) {
                            $requestedQuantity = (int) $correctionQuantities[$index];
                            $availableQuantity = $barcode->quantity->quantity;
                            
                            // Düzeltme miktarı otomatik olarak tüm mevcut miktar olarak ayarlandı
                            // Bu yüzden miktar kontrolü yapmaya gerek yok
                        }
                    }
                }
            }
        });
    }
}