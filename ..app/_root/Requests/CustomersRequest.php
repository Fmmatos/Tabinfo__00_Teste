<?php

namespace Root\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Vendor\Models\Customers;
use Vendor\Rules\__CpfRule;
use Vendor\Rules\__ExistRule;
use Vendor\Rules\__FullNameRule;
use Vendor\Rules\__PasswordRule;

class CustomersRequest extends FormRequest
{
    public function rules(Request $request): array
    {
        $type = $this->get('type') ?? ''; // = $request['type'];
        $position_current = $this->get('position_current') ?? ''; // = $request['position_current'];

        return [
           
            // 'create'
                'name' =>           $position_current == '' ? ['required', new __FullNameRule()] : ['nullable'],
                'cpf' =>            $position_current == '' ? ['required', new __CpfRule(), new __ExistRule(new Customers(), (object)['type_items'=>$type], 0, 'cpf')] : ['nullable'],
                'birth' =>          $position_current == '' ? ['required'] : ['nullable'],
                'phone' =>          $position_current == '' ? ['required'] : ['nullable'],
                'email' =>          $position_current == '' ? ['required', 'email', new __ExistRule(new Customers(), (object)['type_items'=>$type], 0, 'email' )] : ['nullable'],

                'password' =>       $position_current == '' ? ['required', new __PasswordRule($request)] : ['nullable'],

                'zipcode' =>        $position_current == '' ? ['required'] : ['nullable'],
                'address' =>        $position_current == '' ? ['required'] : ['nullable'],
                'number' =>         $position_current == '' ? ['required'] : ['nullable'],
                'neighborhood' =>   $position_current == '' ? ['required'] : ['nullable'],
                'uf' =>             $position_current == '' ? ['required'] : ['nullable'],
                'city' =>           $position_current == '' ? ['required'] : ['nullable'],
            // 'create'
           
        ];
    }

    public function messages(): array
    {
        $cnpj = $this->get('cnpj') ?? ''; // = $request['cnpj'];

        return [
            'name.required' => $cnpj ? 'Preencha o campo nome!' : 'Preencha o campo razão social!',
            'name_1.required' => 'Preencha o campo nome a fantasia!',
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

    // CPF / CNPJ
        // 'cpf' => $type == 1
        //     ? ['required', new __CpfRule(), new __ExistRule(new Customers(), (object)['type_items'=>$request['type']], 0, 'cpf')]
        //     : ['nullable'],

        // 'cnpj' => $type == 2
        //     ? ['required', new __CnpjRule(), new __ExistRule(new Customers(), (object)['type_items'=>$request['type']], 0, 'cnpj' )]
        //     : ['nullable'],

        // 'cpf' => ['required', new __CpfRule(), new __ExistRule(new Customers(), (object)['type_items'=>$request['type']], 0, 'cpf')],
        // 'cnpj' => ['required', new __CnpjRule(), new __ExistRule(new Customers(), (object)['type'=>$request['type']], 0, 'cnpj' )],
        // 'cpf_cnpj' => ['required', new __CpfCnpjRule(), new __ExistRule(new Customers(), (object)['type'=>$request['type']], 0, 'cpf ou cnpj' )],
    // CPF / CNPJ

    // 'name' => ['required'],
    // 'name' => ['required', new __FullNameRule()],

    // 'phone' => ['required'],
    // 'birth' => ['required'],
    // 'sexo' => ['required'],

    // 'email' => ['required', 'email', new __ExistRule(new Customers(), (object)['type_items'=>$request['type']], 0, 'email' )],
    // 'password' => ['required', new __PasswordRule($request)],

    // 'zipcode' => ['required'],
    // 'address' => ['required'],
    // 'number' => ['required'],
    // 'neighborhood' => ['required'],
    // 'uf' => ['required'],
    // 'city' => ['required'],
