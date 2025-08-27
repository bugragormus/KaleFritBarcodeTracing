<?php

namespace App\Http\Requests\Kiln;

use App\Models\Barcode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class KilnStoreRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('kilns','name')
            ],
            'daily_production_average' => [
                'required',
                'numeric',
                'min:0'
            ],
        ];
    }
}
