<?php

/*
* https://cws.correios.com.br/dashboard/pesquisa/5
* https://cws.correios.com.br/dashboard/pesquisa/34
* https://www.youtube.com/watch?v=RL8wnJLXwlY
* https://sfe.correios.com.br/consultarContrato/consultarContrato.jsf#no-back-button
*/

namespace Vendor\Services\Root\Api\Shipping;

use Vendor\Models\XSettings;
use Vendor\Traits\Root\Shipping\__CorreiosTrait;

class __CorreiosService
{
    use __CorreiosTrait;

    // INDEX
        public static function index(object $array, array $return): array
        {
            $XSettings = XSettings::get__(['correios_contrato', 'correios_dr', 'correios_cartao_postagem']);
            if (isset($array->address->zipcode) && $XSettings->correios_contrato && $XSettings->correios_dr && $XSettings->correios_cartao_postagem) {

                // LOCALHOST
                    if (LOCALHOST && LOCALHOST_TESTE_SHIPPING) {
                        return [
                            (object)[
                                "id" => "co03298",
                                "name" => "PAC",
                                "price" => "25,65",
                                "price__" => "25.65",
                                "dias" => 10,
                                "api" => "Correios"
                            ],
                            (object)[
                                "id" => "co03220",
                                "name" => "SEDEX",
                                "price" => "39,30",
                                "price__" => "39.30",
                                "dias" => 4,
                                "api" => "Correios"
                            ]
                        ];
                    }
                // LOCALHOST


                // ON
                    else {

                        // DATA
                            $data_token = [
                                "numero" => $XSettings->correios_cartao_postagem,
                                "contrato" => $XSettings->correios_contrato,
                                "dr" => $XSettings->correios_dr,
                            ];

                            $width = 0;
                            $height = 0;
                            $length = 0;
                            $weight = 0;
                            $price = 0;
                            $weight_cubic_total = 0;
                            foreach ($array->items as $key => $value) {
                                $width += $value->width;
                                $height += $value->height;
                                $length += $value->length;
                                $weight += ($value->weight * 1000 * $value->qty);
                                $price += ($value->price__ * $value->qty);

                                $weight_cubic_total += ($value->width * $value->height * $value->length) * $value->qty;
                            }

                            $weight_cubic = round(pow($weight_cubic_total, 1/3), 2);
                            $width = $weight_cubic < 11 ? 11 : $weight_cubic;
                            $height = $weight_cubic < 2 ? 2 : $weight_cubic;
                            $length = $weight_cubic < 16 ? 16 : $weight_cubic;
                            $weight = $weight < 200 ? 200 : $weight;
                            $weight = $weight > 30000 ? 30000 : $weight;
                            $diameter = hypot($width, $height);

                            $data_price = [
                                "idLote" => "001",
                                "parametrosProduto" => [
                                    [
                                        "coProduto" => "03298",
                                        "nuRequisicao" => "0001",
                                        "nuContrato" => $XSettings->correios_contrato,
                                        "nuDR" => $XSettings->correios_dr,
                                        "tpObjeto" => 2,
                                        "cepOrigem" => number(XSettings::get__(['zipcode'])->zipcode),
                                        "cepDestino" => number($array->address->zipcode),
                                        "largura" => $width,
                                        "altura" => $height,
                                        "comprimento" => $length,
                                        "psObjeto" => $weight,
                                        "diametro" => 0,
                                        // "servicosAdicionais" => ["019"], "vlDeclarado" => price($price, 0, 2, '.', '')
                                    ],
                                    [
                                        "coProduto" => "03220",
                                        "nuRequisicao" => "0002",
                                        "nuContrato" => $XSettings->correios_contrato,
                                        "nuDR" => $XSettings->correios_dr,
                                        "tpObjeto" => 2,
                                        "cepOrigem" => number(XSettings::get__(['zipcode'])->zipcode),
                                        "cepDestino" => number($array->address->zipcode),
                                        "largura" => $width,
                                        "altura" => $height,
                                        "comprimento" => $length,
                                        "psObjeto" => $weight,
                                        "diametro" => 0,
                                        "servicosAdicionais" => ["019"], "vlDeclarado" => price($price, 0, 2, '.', '')
                                    ]
                                ]
                            ];

                            $data_delivery = [
                                "idLote" => "002",
                                "parametrosPrazo" => [
                                    [
                                        "coProduto" => "03298",
                                        "nuRequisicao" => "0001",
                                        "cepOrigem" => number(XSettings::get__(['zipcode'])->zipcode),
                                        "cepDestino" => number($array->address->zipcode),
                                    ],
                                    [
                                        "coProduto" => "03220",
                                        "nuRequisicao" => "0002",
                                        "cepOrigem" => number(XSettings::get__(['zipcode'])->zipcode),
                                        "cepDestino" => number($array->address->zipcode),
                                    ]
                                ]
                            ];
                        // DATA


                        // ENDPOINT
                            $response = self::endpoint($data_token, $data_price, $data_delivery);
                        // ENDPOINT

                    }
                // ON

                
                // RESPONSE
                    // PRICE
                        if (isset($response->array_price) && $response->array_price) {
                            foreach ($response->array_price as $key => $value) {
                                $servico = '';
                                if ($value->coProduto == '03298') {
                                    $servico = 'PAC';
                                }
                                if ($value->coProduto == '03220') {
                                    $servico = 'SEDEX';
                                }
                                if ($value->coProduto == '03158') {
                                    $servico = 'SEDEX10';
                                }

                                if (isset($value->txErro)) {
                                    $return[] = (object)[
                                        'id' => 'co'.$value->coProduto,
                                        'name' => $servico,
                                        'error' => $value->txErro,
                                        'api' => 'Correios',
                                    ];

                                } else if(isset($value->pcFinal)) {
                                    $value->pcFinal = replace('.', '', $value->pcFinal);
                                    $value->pcFinal = replace(',', '.', $value->pcFinal);
                                    $return[] = (object)[
                                        'id' => 'co'.$value->coProduto,
                                        'name' => $servico,
                                        'price' => price($value->pcFinal),
                                        'price__' => $value->pcFinal,
                                        'dias' => 0,
                                        'api' => 'Correios',
                                    ];
                                }
                            }
                        }
                    // PRICE

                    // DELIVERY
                        if (isset($response->array_delivery) && $response->array_delivery) {
                            foreach ($response->array_delivery as $key => $value) {

                                if (isset($value->prazoEntrega)) {
                                    foreach ($return as $key_1 => $value_1) {
                                        if ($value_1->id == 'co'.$value->coProduto) {
                                            if ($value->prazoEntrega <= 1) {
                                                $value->prazoEntrega = 2;
                                            }

                                            $return[$key_1]->dias = $value->prazoEntrega;
                                        }
                                    }
                                }
                            }
                        }
                    // DELIVERY
                // RESPONSE
            }

            return $return;
        }
    // INDEX

}