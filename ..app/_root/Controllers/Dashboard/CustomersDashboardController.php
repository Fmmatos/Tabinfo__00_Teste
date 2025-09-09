<?php

namespace Root\Controllers\Dashboard;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Root\Requests\CustomersRequest;
use Vendor\Mail\__Mail;
use Vendor\Models\Customers;
use Vendor\Models\CustomersAddress;
use Vendor\Rules\__FullNameRule;
use Vendor\Rules\__PasswordRule;

class CustomersDashboardController
{

    // SIGN-IN
        // INDEX
            public function index(Request $request): JsonResponse
            {
                $arr = [];

                // INDICATIONS
                    // if(isset($request['GET']['i']) && $request['GET']['i']){
                    //     $arr['OBJ']['indications'] = Customers::find($request['GET']['i']);

                    //     if(!isset($arr['OBJ']['indications']->id)){
                    //         $arr['OBJ']['indications'] = Customers::where('url', $request['GET']['i'])->first();
                    //     }
                    // }
                // INDICATIONS

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // INDEX





        // STORE
            public function store(CustomersRequest $request): JsonResponse
            {
                $arr = [];

                DB::beginTransaction();
                try {
                    if($request['GET'][0] == 'dashboard' && isset($request['type'])){
                        if(!array_key_exists($request['type'], $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['DASHBOARD']['LOGIN'])){
                            $arr['errors'][] = 'Tipo inválido, selecione se você é '.implode(' ou ', $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['DASHBOARD']['LOGIN']);
                            return json_encode__($arr);
                        }

                    } else {
                        if(!array_key_exists($request['type'], $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['LOGIN'])){
                            $arr['errors'][] = 'Tipo inválido, selecione se você é '.implode(' ou ', $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['LOGIN']);
                            return json_encode__($arr);
                        }
                    }


                    // INDICATIONS
                        // $indications = null;
                        // if(isset($request['GET']['i']) && $request['GET']['i']){
                        //     $indications = Customers::find($request['GET']['i']);

                        //     if(!$indications?->id){
                        //         $indications = Customers::where('url', $request['GET']['i'])->first();
                        //     }        
                        // }
                    // INDICATIONS


                    // CREATE
                        $request['password_temp'] = $request['password'];

                        $customers = Customers::create([
                            'active' => 0,
                            'customers' => $indications?->id ?? null,
                            'type' => $request['type'],
                            'name' => $request['name'],
                            'cpf' => $request['cpf'],
                            // 'sexo' => $request['sexo'],
                            // 'birth' => $request['birth'],
                            'phone' => $request['phone'],
                            'email' => $request['email'],
                            'password' => Hash::make($request['password']),
                        ]);

                        CustomersAddress::create([
                            'main' => 1,
                            'customers' => $customers->id,
                            'name' => $request['name'],
                            'phone' => $request['phone'],

                            'zipcode' => $request['zipcode'],
                            'address' => $request['address'],
                            'number' => $request['number'],
                            'complement' => $request['complement'],
                            'neighborhood' => $request['neighborhood'],
                            'uf' => $request['uf'],
                            'city' => $request['city'],
                        ]);
                    // CREATE


                    // SEND EMAIL
                        // __Mail::send__($request, [ 'table' => 'customers_new', 'type' => $request['type'], 'query' => ['customers' => $customers] ]);
                    // SEND EMAIL


                    $arr['status'] = 200;
                    DB::commit();

                } catch (\Throwable $th) {
                    DB::rollBack();
                    $arr = errors__($th, $arr) ?? [];
                }

                return json_encode__($arr, $request);
            }
        // STORE
    // SIGN-IN




















    // ACCOUNT
        // CREATE / EDIT
            public function create_edit(Request $request): JsonResponse
            {
                $arr = [];

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // CREATE / EDIT





        // UPDATE
            public function update(Request $request): JsonResponse
            {
                $arr = [];

                if($request['form'] == 'dados'){
                        $request->validate([
                            'name' => ['required'],
                            // 'name' => ['required', new __FullNameRule()],
                            'phone' => ['required'],
                            'birth' => ['required'],
                            'sexo' => ['required'],
                        ]);

                        Customers::find_id($request->user()->id)->update([
                            'name' => $request['name'],
                            'phone' => $request['phone'],
                            'birth' => $request['birth'],
                            'sexo' => $request['sexo'],
                        ]);
                    }
                // DADOS


                // PHOTO
                    if($request['form'] == 'photo'){
                        Customers::find_id($request->user()->id)->update([
                            'image' => [ 'request' => $request, 'type' => 'image' ]
                        ]);
                    }
                // PHOTO


                // PASSWORD
                    if($request['form'] == 'password'){
                        $request->validate([
                            'password' => ['required', new __PasswordRule($request)],
                        ]);

                        Customers::find_id($request->user()->id)->update([
                            'password' => Hash::make($request['password']),
                        ]);
                    }
                // PASSWORD

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // UPDATE
    // ACCOUNT

}