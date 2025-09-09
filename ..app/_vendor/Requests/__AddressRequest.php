<?php

namespace Vendor\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Validator;
use Vendor\Models\Customers;
use Vendor\Rules\__CnpjRule;
use Vendor\Rules\__CpfCnpjRule;
use Vendor\Rules\__CpfRule;
use Vendor\Rules\__PasswordRule;

class __AddressRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        $model = new Customers();

        $filter = (object)[];
        $filter->type = $request['type'];
        $filter->filter = '';

        return [
            'name' => ['required', new __PasswordRule($request)],

            // 'email' => ['required', 'email'],
            'phone' => ['required'],

            // 'cpf' => ['required', new __CpfRule()],
            // 'cnpj' => ['required', new __CnpjRule()],
            // 'cpf_cnpj' => ['required', new __CpfCnpjRule()],

            'zipcode' => ['required'],
            'address' => ['required'],
            'number' => ['required'],
            'neighborhood' => ['required'],
            'uf' => ['required'],
            'city' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            // 'name.required' => 'Preencha o campo nome!',
        ];

    }
    
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}