<?php

namespace Vendor\Traits\Root\Pay;

use Illuminate\Support\Facades\Http;
use Vendor\Models\XSettings;

trait __AppMaxTrait
{

    // ENDPOINT
        protected static function endpoint(array $data, string $endpoint, string $method): object
        {
            // HTTP
                $token = XSettings::get__(['appmax_token'])->appmax_token??'';
                // $sandbox = '1B0456F4-118DF5A2-C87B1C1A-6F304131';

                if ($token) {
                    // BASE URL
                        $baseUrl = 'https://admin.appmax.com.br/api/v3';
                        if(isset($sandbox)){
                            $token = $sandbox;
                            $baseUrl = 'https://homolog.sandboxappmax.com.br/api/v3';
                        }
                    // BASE URL

                    // DATA
                        $data['access-token'] = $token;
                    // DATA

                    // GET
                        if ($method == 'GET') {
                            $response = Http::withHeaders([
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ])->get($baseUrl.$endpoint, $data);
                        }
                    // GET

                    // POST/PUT/DELETE
                        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE' || $method == 'PATCH') {
                            $response = Http::withHeaders([
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                            ])->$method($baseUrl.$endpoint, $data);
                        }
                    // POST/PUT/DELETE

                    $array = json_decode($response->body());
                    
                    if (PROG) {
                        $arr = [
                            $endpoint => [
                                'data' => $data,
                                'response' => $array,
                            ]
                        ];
                        GET_pre_fixed_set($arr, 'AppMax', 1);
                    }

                } else {
                    $response = [];
                    $array = (object)['error' => 'Token do AppMax não Cadastrado!'];
                    GET_pre_fixed_set('Token do AppMax não Cadastrado!');
                }
            // HTTP




            // JSON
                if (!file_exists(DIR_D.'/json/pay/')) {
                    mkdir(DIR_D.'/json/pay/', 0755, true);
                }

                $endpoint__ = str_replace('/', '_', $endpoint);
                $endpoint__ = explode('?', $endpoint__)[0];

                $file = fopen(DIR_D.'/json/pay/'.($data['metadata']['table']??'appmax').'__'.($data['metadata']['id']??'0').' - '.date('Y-m-d H.i.s').' - '.$endpoint__.' - AppMax.json', 'w');
                fwrite($file, json_encode([ 
                    'endpoint' => $endpoint, 
                    'data' => $data, 
                    'response' => $array, 
                    'http_status' => $response->status() ?? 0,
                    'http_body' => $response->body() ?? ''
                ]));
                fclose($file);
            // JSON




            // RESPONSE
                // SUCCESS
                    if($array->success){
                        if (isset($array->data) && !isset($array->error)) {
                            $return = (object)$array->data;
                            $return->sucess = true;
                        }
                        else if (isset($array->id) && !isset($array->error)) {
                            $return = (object)$array;
                            $return->sucess = true;
                        }
                        else if (is_array($array) && count($array) > 0 && !isset($array['error'])) {
                            $return = (object)['results' => $array];
                            $return->sucess = true;
                        }
                    }
                // SUCCESS

                // ERROR
                    else {
                        $_GET['pre'][] = $array;
                        if (isset($array->error)) {
                            $return = (object)['error' => $array->error];
                        }

                        else if (isset($array->errors)) {
                            $errorMessages = [];
                            foreach ($array->errors as $field => $messages) {
                                if (is_array($messages)) {
                                    foreach ($messages as $message) {
                                        $errorMessages[] = $field . ': ' . $message;
                                    }
                                } else {
                                    $errorMessages[] = $field . ': ' . $messages;
                                }
                            }
                            $return = (object)['error' => implode(' | ', $errorMessages)];
                        }

                        else if (isset($array->message)) {
                            $return = (object)['error' => $array->message];
                        }
                        
                        else if (isset($array->text)) {
                            $return = (object)['error' => $array->text];
                        }

                        else if (isset($array->data)) {
                            foreach($array->data as $key => $value) {
                                foreach($value as $key_1 => $value_1) {
                                    $return = (object)['error' => $value_1];
                                }
                            }
                        }

                        else {
                            $return = (object)['error' => 'Ocorreu um erro ao processar o pagamento. Tente novamente.'];
                        }
                    }
                // ERROR
            // RESPONSE

            // return $return;

            return $return;
        }
    // ENDPOINT

}