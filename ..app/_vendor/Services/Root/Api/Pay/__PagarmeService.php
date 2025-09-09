<?php

/*
* https://docs.pagar.me/reference/introdu%C3%A7%C3%A3o-1
*/

namespace Vendor\Services\Root\Api\Pay;

use Illuminate\Http\Request;
use Vendor\Models\Webhooks;
use Vendor\Models\XSettings;
use Vendor\Traits\Root\Pay\__PagarmeTrait;

class __PagarmeService
{
    use __PagarmeTrait;

    // PAY
        public function pay(array $params, string $method): array|object
        {

            // PARAMS
                $array = [
                    // METADATA
                        'metadata' => [
                            'id' => $params['id'],
                            'table' => $params['table'],
                            'code' => $params['code'],
                        ],
                    // METADATA

                    // INFOS
                        // 'amount' => (int)($params['price']) * 100,
                        'items' =>  [
                            'code' => (string)$params['id'],
                            'description' => (string)$params['name'],
                            'amount' => (int)$params['price'] * 100,
                            'quantity' => 1,
                        ],
                    // INFOS

                    // CUSTOMERS
                        'customer' => (isset($request['customer_id']) && $request['customer_id']) ? $request['customer_id'] : $this->customers($params['customers']),
                    // CUSTOMERS
                ];


                // SHIPPING
                    if (isset($params['customers']) && $params['customers']) {
                        $array['shipping'] = $this->shipping($params['customers']);
                    }
                // SHIPPING


                // PAY_METHOD
                    switch ($method) {
                        // CARD_CREDIT
                            case 'card_credit':
                                $array['payments']['pay_method'] = 'card_credit'; 
                                $array['payments']['card_credit']['statement_descriptor'] = limit1(trim(not('acentos_all', XSettings::get__(['name_site'])->name_site, '')), 13);
                                $array['payments']['card_credit']['installments'] = $params['card_installments']>1 ? (int)$params['card_installments'] : 1; 

                                // CARD
                                    if (isset($params['card_id']) && $params['card_id']) {
                                        $array['payments'][0]['card_credit']['card_id'] = $params['card_id'];

                                    } else {
                                        $expiration_date = explode('/', $params['card']->expiration_date);
                                        $array['payments'][0]['card_credit']['card'] = [
                                            'holder_name' => limit1(trim(not('acentos_all', $params['card']->name, ' ')), 64),
                                            'number' => preg_replace('/\D/', '', $params['card']->number),
                                            'exp_month' => $expiration_date[0],
                                            'exp_year' => isset($expiration_date[1]) ? substr((int)$expiration_date[1] >= 2000 ? (int)$expiration_date[1] : 2000 + (int)$expiration_date[1], 2) : '',
                                            'cvv' => $params['card']->cvv,
                                            'billing_address' => [
                                                'line_1' => not('accents', $params['customers']->number).', '.not('accents', explode('-', $params['customers']->address)[0]).', '.not('accents', $params['customers']->neighborhood),
                                                'line_2' => $params['customers']->complement ? ' '.not('accents', $params['customers']->complement) : '',
                                                'zip_code' => not('accents', number($params['customers']->zipcode)),
                                                'city' => not('accents', $params['customers']->city),
                                                'uf' => not('accents', $params['customers']->uf),
                                                'country' => 'br',
                                            ],
                                        ];
                                    }
                                // CARD

                                break;
                        // CARD_CREDIT

                        // BOLETO
                            case 'boleto':
                                $array['payments']['pay_method'] = 'bolbradesco';
                                $array['payments']['boleto']['due_at'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (!empty($params['boleto_expiration']) ? $params['boleto_expiration'] : 3), date('Y')))."T23:59:59Z";
                                $array['payments']['boleto']['type'] = 'DM';
                                $array['payments']['boleto']['instructions'] = $params['instructions'] ?? '';
                                break;
                        // BOLETO

                        // PIX
                            case 'pix':
                                $array['payments']['pay_method'] = 'pix';
                                $array['payments']['pix'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (!empty($params['pix_expiration']) ? $params['pix_expiration'] : 1), date('Y')))."T23:59:59Z";
                                break;
                        // PIX
                    }
                // PAY_METHOD
            // PARAMS


            // ENDPOINT
                return $this->endpoint($array, '/orders');
            // ENDPOINT
        }
    // PAY










    // CUSTOMERS
        public function customers(object $customers): array
        {
            $array = [
                'name' => limit1($customers->name, 64),
                'email' => LOCALHOST ? 'joaosilva@silvao.com' : $customers->email,

                'type' => (isset($customers->cnpj) && $customers->cnpj) ? 'company' : 'individual',
                'document_type' => (isset($customers->cnpj) && $customers->cnpj) ? 'cnpj' : 'cpf',
                'document' => (isset($customers->cnpj) && $customers->cnpj) ? number($customers->cnpj) : number($customers->cpf),

                'phones' => [
                    'home_phone' => [
                        'country_code' => '55',
                        'area_code' => phone_ddd($customers->phone),
                        'number' => phone_number($customers->phone),
                    ]
                ],

                'address' => [
                    'line_1' => not('accents', $customers->number).', '.not('accents', explode('-', $customers->address)[0]).', '.not('accents', $customers->neighborhood),
                    'line_2' => $customers->complement ? ' '.not('accents', $customers->complement) : '',
                    'zip_code' => not('accents', number($customers->zipcode)),
                    'city' => not('accents', $customers->city),
                    'uf' => not('accents', $customers->uf),
                    'country' => 'br',
                ],

                'metadata' => [
                    'id' => (string)$customers->id,
                    'type' => (string)$customers->type
                ]    
            ];
            return $array;
        }
    // CUSTOMERS










    // SHIPPING
        public function shipping(object $customers): array
        {
            $array = [
                'recipient_name' => limit1(name($customers->name), 20),
                'recipient_phone' => '55'.phone_ddd($customers->phone).phone_number($customers->phone),
                'amount' => 0,

                'address' => [
                    'line_1' => not('accents', $customers->number).', '.not('accents', explode('-', $customers->address)[0]).', '.not('accents', $customers->neighborhood),
                    'line_2' => $customers->complement ? ' '.not('accents', $customers->complement) : '',
                    'zip_code' => not('accents', number($customers->zipcode)),
                    'city' => not('accents', $customers->city),
                    'uf' => not('accents', $customers->uf),
                    'country' => 'br',
                ],
            ];
            return $array;
        }
    // SHIPPING










    // WEBHOOKS
        public function webhooks(Request $request): object
        {
            $return = (object)[];
            if (isset($request['data']['status'])) {
                $client_id = XSettings::get__(['pagarme_client_id'])->pagarme_client_id;
                if ($request['account']['id'] == $client_id) {

                    // INFOS
                        $return->id = $request['data']['id'];
                        $return->gateway = 'Pagarme';
                    // INFOS


                    // METADATA
                        $return->metadata = (object)[];
                        $return->metadata->id = $request['data']['metadata']['id'];
                        $return->metadata->table = $request['data']['metadata']['table'];
                        $return->metadata->code = $request['data']['metadata']['code'];
                    // METADATA


                    // STATUS
                        // PAID
                            if (strtolower($request['data']['status']) == 'paid') {
                                $return->status = 20;
                            }
                        // PAID

                        // CANCELLED
                            if (strtolower($request['data']['status']) == 'canceled') {
                                $return->status = 10;
                            }
                        // CANCELLED

                        // REFUNDED
                            if (strtolower($request['data']['status']) == 'refunded') {
                                $return->status = 11;
                            }
                        // REFUNDED
                    // STATUS
                }
            }

            Webhooks::create([
                'type' => 'Pagarme',
                'status' => isset($return->status) ? $return->status : 0,
                'gateway_id' => isset($request['data']['id']) ? $request['data']['id'] : '',
                'gateway_webhooks_id' => isset($request['data']['id']) ? $request['data']['id'] : '',
                'return__' => json_encode($return),
                'reponse' => '',
                'request' => json_encode($request->all()),
            ]);

            return $return;
        }
    // WEBHOOKS





















    // DEFAULT
        // JS
            // public function js(): array
            // {
            //     $key = XSettings::get__(['mercado_pago_public_key'])->mercado_pago_public_key;
            //     $return = [
            //         'script' => '
            //             window.Mercadopago.setPublishableKey(`'.$key.'`);

            //             var token = {};
            //             function MercadoPago__js(json) {
            //                 form = {
            //                     cardholderName: 		json.card_name,
            //                     cardNumber:				json.card_number.replace(/\s/g, ``),
            //                     cardExpirationMonth:	json.card_expiration_month,
            //                     cardExpirationYear:		parseInt(json.card_expiration_year) >= 2000 ? parseInt(json.card_expiration_year) : 2000 + parseInt(json.card_expiration_year),
            //                     securityCode:			json.card_cvv,
            //                     docType:				json.card_doc_type,
            //                     docNumber:				json.card_doc_number,
            //                 };
            //                 // console.log(form);


            //                 return new Promise((resolve, reject) => {
            //                     // BRAND
            //                         window.Mercadopago.getPaymentMethod({ "bin": form.cardNumber.substring(0, 6) }, function setPaymentMethodInfo(status, response) {
            //                             if (status === 200) {
            //                                 token.brand = response[0].id;

            //                                 // TOKEN
            //                                     try {
            //                                         window.Mercadopago.createToken(form, function sdkResponseHandler(status, response) {
            //                                             if (status === 200) {
            //                                                 token.token = response.id;
            //                                                 resolve(token);

            //                                             } else {
            //                                                 console.error(`token error`, response);
            //                                                 reject("Cartão inválido!!!");
            //                                             }
            //                                         });
            //                                     } catch (e) {
            //                                         console.error("Exceção ao criar token", e);
            //                                         reject("Cartão inválido!!");
            //                                     }
            //                                 // TOKEN

            //                             } else {
            //                                 console.error(`brand error`, response);
            //                                 reject("Cartão inválido!");
            //                             }
            //                         // BRAND
            //                     });
            //                 });

            //             }
            //         ',
            //     ];

            //     return $return;
            // }
        // JS
    // DEFAULT


}
