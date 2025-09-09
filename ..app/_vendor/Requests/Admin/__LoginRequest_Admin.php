<?php

namespace Vendor\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class __LoginRequest_Admin extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Preencha o campo email!',
            'email.email' => 'Preencha o campo email corretamente!',
            'password.required' => 'Preencha o campo senha!',
        ];

    }
}
