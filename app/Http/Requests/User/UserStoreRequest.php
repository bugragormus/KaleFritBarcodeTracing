<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
                'string'
            ],
            'phone' => [
                'required',
                'digits:11',
                Rule::unique('users','phone')
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('users','email')
            ],
            'registration_number' => [
                'required',
                'string',
                Rule::unique('users','registration_number')
            ],
            'password' => [
                'required',
                'min:6',
                'string'
            ]
        ];
    }
}
