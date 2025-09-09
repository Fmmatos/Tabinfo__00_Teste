<?php

namespace Vendor\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

class __LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Preencha o campo email!',
            'email.email' => 'Preencha o campo email corretamente!',
            'password.required' => 'Preencha o campo senha!',
        ];

    }
}
