<?php

/*
* https://docs.melhorenvio.com.br/reference/calculo-de-fretes-por-produtos
*/

namespace Vendor\Services\Root\Api\Shipping;

use Vendor\Models\XSettings;
use Vendor\Traits\Root\Shipping\__MelhorEnvioTrait;

class __MelhorEnvioService
{
    use __MelhorEnvioTrait;

    // INDEX
        public static function index(object $array, array $return): array
        {
            // CART
                if (isset($array->address->zipcode)) {

                    // LOCALHOST
                        if (LOCALHOST && LOCALHOST_TESTE_SHIPPING) {
                            return [
                                (object)[
                                    "id" => "me1",
                                    "name" => "PAC",
                                    "price" => "25,65",
                                    "price__" => "25.65",
                                    "dias" => 10,
                                    "api" => "Melhor Envio"
                                ],
                                (object)[
                                    "id" => "me2",
                                    "name" => "SEDEX",
                                    "price" => "39,30",
                                    "price__" => "39.30",
                                    "dias" => 4,
                                    "api" => "Melhor Envio"
                                ]
                            ];
                        }
                    // LOCALHOST


                    // ON
                        else {

                            // DATA
                                // $products = array_map(function($item) {
                                //     return [
                                //         'id' => $item->id,
                                //         'width' => $item->width,
                                //         'height' => $item->height,
                                //         'length' => $item->length,
                                //         'weight' => $item->weight,
                                //         'insurance_value' => $item->price__,
                                //         'quantity' => $item->qty,
                                //     ];
                                // }, $array->items);
                            
                                $x=0;
                                $products = [];
                                foreach ($array->items as $key => $value) {
                                    $products[$x] = array();
                                    $products[$x]['id'] = $value->id;
                                    $products[$x]['width'] = $value->width;
                                    $products[$x]['height'] = $value->height;
                                    $products[$x]['length'] = $value->length;
                                    $products[$x]['weight'] = $value->weight;
                                    $products[$x]['insurance_value'] = $value->price__;
                                    $products[$x]['quantity'] = $value->qty;
                                    $x++;
                                }

                                $data = [
                                    'from' => [
                                        'postal_code' => number(XSettings::get__(['zipcode'])->zipcode)
                                    ],
                                    'to' => [
                                        'postal_code' => number($array->address->zipcode)
                                    ],
                                    'products' => $products,
                                ];
                            // DATA


                            // ENDPOINT
                                $response = self::endpoint($data, '/me/shipment/calculate');
                            // ENDPOINT


                            // RESPONSE
                                if (isset($response->array) && $response->array) {
                                    foreach ($response->array as $key => $value) {
                                        if (isset($value->name) && in_array($value->name, ['PAC', 'SEDEX'])) {
                                            if (isset($value->error)) {
                                                $return[] = (object)[
                                                    'id' => 'me'.$value->id,
                                                    'name' => $value->name,
                                                    'error' => $value->error,
                                                    'api' => 'Melhor Envio',
                                                ];

                                            } else if(isset($value->name) && isset($value->price)) {
                                                $return[] = (object)[
                                                    'id' => 'me'.$value->id,
                                                    'name' => $value->name,
                                                    'price' => price($value->price),
                                                    'price__' => $value->price,
                                                    'dias' => $value->delivery_time,
                                                    'api' => 'Melhor Envio',
                                                ];
                                            }
                                        }
                                    }
                                }
                            // RESPONSE
                        }
                    // ON

                }
            // CART

            return $return;
        }
    // INDEX

}
