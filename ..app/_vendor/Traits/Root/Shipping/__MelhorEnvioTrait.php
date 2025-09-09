<?php

namespace Vendor\Traits\Root\Shipping;

use Illuminate\Support\Facades\Http;
use Vendor\Models\XSettings;

trait __MelhorEnvioTrait
{


    // ENDPOINT
        protected static function endpoint(array $data, $endpoint): object
        {
            $return = (object)[];

            $url = 'melhorenvio.com.br';
            // $url = 'sandbox.melhorenvio.com.br';

            $XSettings = XSettings::get__(['melhor_envio_token']);
            if ($XSettings->melhor_envio_token) {

                // HTTP
                    $response = Http::withHeaders([
                        'Accept: application/json',
                        'Content-Type' => 'application/json',
                        'Authorization' => 'Bearer '.$XSettings->melhor_envio_token
                    ])->post('https://'.$url.'/api/v2'.$endpoint, $data);

                    $array = json_decode($response->body());
                    if (PROG) {
                        GET_pre_fixed_set($array, 'MelhorEnvio');
                    }
                // HTTP





                // RESPONSE
                    if (isset($array->message) && $array->message) {
                        $return = (object)['error' => 'Ocorreu um erro na compra, tente novamente mais tarde!'];

                    } else {
                        $return = (object)['array' => $array];
                    }
                // RESPONSE

            }

            return $return;
        }
    // ENDPOINT

}
