<?php

namespace Vendor\Traits\Root\Media;

use Illuminate\Support\Facades\Http;

trait __EvolutionApiTrait
{

    // ENDPOINT
        protected static function endpoint(array $data, string $endpoint): object
        {
            // HTTP
                $token = env('EVOLUTIONAPI_TOKEN');
                $instance = env('EVOLUTIONAPI_INSTANCE');
                $number = env('EVOLUTIONAPI_NUMBER');
                $server = env('EVOLUTIONAPI_SERVER');

                $method = (isset($data[0]) && $data[0]=='get') ? 'get' : 'post';

                $response = Http::withHeaders([
                    'Accept: application/json',
                    'Content-Type' => 'application/json',
                    'apikey' => $token
                ])->$method($server.$endpoint.'/'.$instance, $data);

                $array = json_decode($response->body());
                if (PROG) {
                    GET_pre_fixed_set($array, 'EvolutionApi');
                }
            // HTTP





            // JSON
                if (!file_exists(DIR_D.'/json/evolution_api/'.$number.'/')) {
                    mkdir(DIR_D.'/json/evolution_api/'.$number.'/', 0755, true);
                }

                $file = fopen(DIR_D.'/json/evolution_api/'.$number.'/'.date('Y-m-d H.i.s').' - '.replace('/', '_', $endpoint).'.json', 'w');
                fwrite($file, json_encode([ 'data' => $data, 'array' => $array, 'response' => $response ]));
                fclose($file);
            // JSON





            // RESPONSE
                // OK
                    if (isset($array->id) && $array->id) {
                        $return = $array;
                    }
                // OK


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
