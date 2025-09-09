<?php

namespace Vendor\Traits\Root\Shipping;

use Illuminate\Support\Facades\Http;
use Vendor\Models\XSettings;

trait __CorreiosTrait
{


    // ENDPOINT
        protected static function endpoint(array $data_token, array $data_price, array $data_delivery): object
        {
            $return = (object)[];

            $url = 'api.correios.com.br';

            $XSettings = XSettings::get__(['correios_usuario', 'correios_code_acess', 'correios_token', 'correios_token_expiration']);
            if ($XSettings->correios_usuario && $XSettings->correios_code_acess) {

                // HTTP
                    if ($XSettings->correios_token && $XSettings->correios_token_expiration > date('Y-m-d H:i:s')) {
                        $token = $XSettings->correios_token;

                    } else {
                        // TOKEN
                            $response = Http::withHeaders([
                                // 'Accept: application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Basic '.base64_encode($XSettings->correios_usuario.':'.$XSettings->correios_code_acess)
                            ])->post('https://'.$url.'/token/v1/autentica/cartaopostagem', $data_token);

                            $array = json_decode($response->body());
                            if (PROG) {
                                GET_pre_fixed_set($array, 'Correios');
                            }
                            GET_pre_fixed_set($array);
                        // TOKEN

                        if (isset($array->token)) {
                            XSettings::find_id(XSettings::where('fields', 'correios_token')->first()->id)->update([
                                'value' => $array->token
                            ]);
                            XSettings::find_id(XSettings::where('fields', 'correios_token_expiration')->first()->id)->update([
                                'value' => date__($array->expiraEm, 'Y-m-d H:i:s')
                            ]);
                            $token = $array->token;
                        }
                    }


                    if (isset($token)) {
                        // PRICE
                            $response_price = Http::withHeaders([
                                // 'Accept: application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer '.$token
                            ])->post('https://'.$url.'/preco/v1/nacional', $data_price);

                            $array_price = json_decode($response_price->body());
                            if (PROG) {
                                GET_pre_fixed_set($array_price, 'Correios Price');
                            }
                        // PRICE

                        // DELIVERY
                            $response_delivery = Http::withHeaders([
                                // 'Accept: application/json',
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer '.$token
                            ])->post('https://'.$url.'/prazo/v1/nacional', $data_delivery);

                            $array_delivery = json_decode($response_delivery->body());
                            if (PROG) {
                                GET_pre_fixed_set($array_delivery, 'Correios Delivery');
                            }
                        // DELIVERY
                    }
                // HTTP





                // RESPONSE
                    if (!isset($token) || isset($array_price->msgs) && $array_price->msgs) {
                        XSettings::find_id(XSettings::where('fields', 'correios_token')->first()->id)->update([
                            'value' => ''
                        ]);
                        XSettings::find_id(XSettings::where('fields', 'correios_token_expiration')->first()->id)->update([
                            'value' => ''
                        ]);

                        $return = (object)['error' => 'Ocorreu um erro na compra, tente novamente mais tarde!'];

                    } else {
                        $return = (object)['array_price' => $array_price, 'array_delivery' => $array_delivery];
                    }
                // RESPONSE

            }

            return $return;
        }
    // ENDPOINT

}
