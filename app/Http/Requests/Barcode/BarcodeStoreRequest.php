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
                'integer'
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
            'quantity' => [
                'required',
                'integer'
            ],
            'print' => [
                'nullable',
            ],
        ];
    }
}
