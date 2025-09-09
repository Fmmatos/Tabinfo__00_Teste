<?php

namespace Root\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Root\Requests\CustomersRequest;
use Vendor\Models\Customers;
use Vendor\Models\XSettings;
use Vendor\Rules\__FullNameRule;
use Vendor\Rules\__PasswordRule;
use Vendor\Services\__LoginService;

class CustomersController
{

    // SIGN-IN
        // INDEX
            public function index(Request $request): JsonResponse
            {
                $arr = [];

                if(isset($request['GET']['i']) AND $request['GET']['i']){
                    $arr['OBJ']['indications'] = Customers::find($request['GET']['i']);

                    if(!isset($arr['OBJ']['indications']->id)){
                        $arr['OBJ']['indications'] = Customers::where('url', $request['GET']['i'])->first();
                    }
                }

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // INDEX





        // STORE
            public function store(CustomersRequest $request): JsonResponse
            {
                $arr = [];


                // CREATE
                    if(!isset($request['position_current'])){

                        // VALIDADE
                            // DASHBOARD
                                if($request['GET'][0] == 'dashboard' && isset($request['type'])){
                                    if(!array_key_exists($request['type'], $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['DASHBOARD']['LOGIN'])){
                                        $arr['errors'][] = 'Tipo inválido, selecione se você é '.implode(' ou ', $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['DASHBOARD']['LOGIN']);
                                        return json_encode__($arr, $request);
                                    }
                                }
                            // DASHBOARD


                            // ROOT
                                else {
                                    if(!array_key_exists($request['type'], $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['LOGIN'])){
                                        $arr['errors'][] = 'Tipo inválido, selecione se você é '.implode(' ou ', $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['LOGIN']);
                                        return json_encode__($arr, $request);
                                    }
                                }
                            // ROOT
                        // VALIDADE


                        // INDICATIONS
                            if(isset($request['GET']['i']) AND $request['GET']['i']){
                                $indications = Customers::find($request['GET']['i']);

                                if(!isset($indications->id)){
                                    $indications = Customers::where('url', $request['GET']['i'])->first();
                                }        
                            }
                        // INDICATIONS


                        // ARRAY
                            $token = token(5, false, true);
                            $save = [
                                'active' => 1,
                                'customers' => $indications->id ?? null,
                                
                                'type',
                                'name',
                                'cpf',
                                'birth',
                                'phone',
                                'email',
                                'sexo',
                                'password',

                                // 'customers_address' => 1,
                            ];
                        // ARRAY

                    }
                // CREATE


                // SAVE
                    if(isset($save)){
                        $arr = __LoginService::sign_up($request, new Customers(), $save, $arr);

                        // SUBSCRIPTION
                            // if(!isset($request['position_current'])){
                            //     COOKIES_CREATE('sign_up_id', $arr['id']);
                            //     COOKIES_CREATE('sign_up_type', $request['type']);

                            //     $arr['status'] = 200;
                            // }
                        // SUBSCRIPTION


                        // GO HOME
                            if(!isset($request['position_current'])){
                                $customers = Customers::find($arr['id']);
    
                                if(isset($customers->id) && $customers->id){
                                    $arr['token'] = $customers->createToken(time())->plainTextToken;
                                    COOKIES_CREATE('rootAuth', $customers->id);
                                    COOKIES_CREATE('dashboardAuth', $customers->id);

                                    COOKIES_DELETE('sign_up_id');
                                    COOKIES_DELETE('sign_up_type');
        
                                    $arr['status'] = 200;
                                }
                            }
                        // GO HOME

                    }
                // SAVE

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
                            // 'name' => ['required'],
                            'name' => ['required', new __FullNameRule()],
                            'phone' => ['required'],
                            // 'birth' => ['required'],
                            // 'sexo' => ['required'],
                        ]);

                        Customers::find_id($request->user()->id)->update([
                            'name' => $request['name'],
                            'phone' => $request['phone'],
                            // 'birth' => $request['birth'],
                            // 'sexo' => $request['sexo'],
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