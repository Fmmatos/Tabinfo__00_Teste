<?php

namespace Vendor\Traits\Root\Pay;

use Illuminate\Support\Facades\Http;
use Vendor\Models\XSettings;

trait __PagarmeTrait
{

    // ENDPOINT
        protected function endpoint(array $data, string $endpoint): object
        {
            // HTTP
                $response = Http::withHeaders([
                    'Accept: application/json',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic '.base64_encode(XSettings::get__(['pagarme_client_secret'])->pagarme_client_secret.':')
                ])->post('https://api.pagar.me/core/v5'.$endpoint, $data);

                $array = json_decode($response->body());
                if (PROG) {
                    GET_pre_fixed_set($array, 'Pagarme');
                }
            // HTTP





            // JSON
                if (!file_exists(DIR_D.'/json/pay/')) {
                    mkdir(DIR_D.'/json/pay/', 0755, true);
                }

                $file = fopen(DIR_D.'/json/pay/'.$data['metadata']['table'].'__'.$data['metadata']['id'].' - '.date('Y-m-d H.i.s').' - PagarMe.json', 'w');
                fwrite($file, json_encode([ 'data' => $data, 'array' => $array, 'response' => $response ]));
                fclose($file);
            // JSON





            // RESPONSE
                if (isset($array->id) && $array->id) {
                    $return = $array;

                } else {
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
            // RESPONSE

            return $return;
        }
    // ENDPOINT

}
