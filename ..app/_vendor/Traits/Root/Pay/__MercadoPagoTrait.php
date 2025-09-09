<?php

namespace Vendor\Traits\Root\Pay;

use Illuminate\Support\Facades\Http;
use Vendor\Models\XSettings;

trait __MercadoPagoTrait
{

    // ENDPOINT
        protected static function endpoint(array $data, string $endpoint, string $method): object
        {
            // HTTP
                $token = XSettings::get__(['mercado_pago_access_token'])->mercado_pago_access_token;

                if ($token) {

                    // GET
                        if ($method == 'GET') {
                            $response = Http::withHeaders([
                                'Authorization' => 'Bearer '.$token,
                            ])->get('https://api.mercadopago.com/v1'.$endpoint);
                        }
                    // GET

                    // POST
                        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
                            $response = Http::withHeaders([
                                'Accept' => 'application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer '.$token,
                                'X-Idempotency-Key' => $data['metadata']['id'] ?? '',
                            ])->$method('https://api.mercadopago.com/v1'.$endpoint, $data);
                        }
                    // POST


                    $array = json_decode($response->body());
                    if (PROG) {
                        GET_pre_fixed_set($endpoint, 'MercadoPago_endpoint');
                        GET_pre_fixed_set($data, 'MercadoPago_data');
                        GET_pre_fixed_set($array, 'MercadoPago');
                    }

                } else {
                    $response = [];
                    $array = (object)['message' => 'Token do MercadoPago não Cadastrado!'];
                    GET_pre_fixed_set('Token do MercadoPago não Cadastrado!');
                }
            // HTTP





            // JSON
                if (!file_exists(DIR_D.'/json/pay/')) {
                    mkdir(DIR_D.'/json/pay/', 0755, true);
                }

                $endpoint__ = str_replace('/', '_', $endpoint);
                $endpoint__ = explode('?', $endpoint__)[0];

                $file = fopen(DIR_D.'/json/pay/'.($data['metadata']['table']??'').'__'.($data['metadata']['id']??'').' - '.date('Y-m-d H.i.s').' - '.$endpoint__.' - MercadoPago.json', 'w');
                fwrite($file, json_encode([ 'endpoint' => $endpoint, 'data' => $data, 'array' => $array, 'response' => $response ]));
                fclose($file);
            // JSON





            // RESPONSE
                // ARRAY
                    if (is_array($array) && isset($array[0]->id) && $array[0]->id) {
                        // CARDS
                            if (isset($array[0]->cardholder)) {
                                $TEMP = $array;
                                $array = (object)[];
                                $array->results = $TEMP;
                            }
                        // CARDS
                    }
                // ARRAY

                // SUCCESS
                    if (isset($array->id) && $array->id) {
                        $return = (object)$array;
                    }

                    else if(isset($array->results) && $array->results) {
                        $return = (object)$array;

                    }
                // SUCCESS

                // ERROR
                    else {
                        if (isset($array->errors)) {
                            $return = (object)['error' => ''];
                            foreach ($array->errors as $k1 => $v1) {
                                $return->error .= $k1;
                                foreach ($v1 as $k2 => $v2) {
                                    $return->error .= ' - '.$v2;
                                }
                            }

                        } else if(isset($array->message) && $array->message) {
                            $return = (object)['error' => $array->message];

                        } else if(isset($array->charges[0]->last_transaction->gateway_response->errors[0]->message) && $array->charges[0]->last_transaction->gateway_response->errors[0]->message) {
                            $return = (object)['error' => $array->charges[0]->last_transaction->gateway_response->errors[0]->message];

                        } else {
                            $return = (object)['error' => 'Ocorreu um erro na compra, tente novamente mais tarde!'];
                        }
                    }
                // ERROR
            // RESPONSE

            return $return;
        }
    // ENDPOINT

}
