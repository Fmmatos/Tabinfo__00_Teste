<?php

/*
* https://www.mercadopago.com.br/developers/pt/docs/checkout-api/integration-configuration/card/integrate-via-cardform
*/

namespace Vendor\Services\Root\Api\Pay;

use Illuminate\Http\Request;
use Vendor\Models\Webhooks;
use Vendor\Models\XSettings;
use Vendor\Services\Root\__PayService;
use Vendor\Traits\Root\Pay\__MercadoPagoTrait;

class __MercadoPagoService
{
    use __MercadoPagoTrait;

    // PAY
        // CREATE
            public static function pay(Request $request, array $data, string $method): object
            {

                // DATA
                    $array = [
                        // METADATA
                            'metadata' => [
                                'id' => $data['id'],
                                'table' => $data['table'],
                                'code' => $data['code'],
                            ],
                        // METADATA

                        // INFOS
                            "external_reference" => $data['id'],
                            'transaction_amount' => (float)$data['price'],
                            'description' => $data['name'],
                        // INFOS

                        // CUSTOMERS
                            'payer' => self::customers_data($data['customers']),
                        // CUSTOMERS
                    ];


                    // PAY_METHOD
                        switch ($method) {
                            // CARD_CREDIT
                                case 'card_credit':
                                    // CARD_ID
                                        if (!empty($data['card_id'])) {
                                            $customers = self::customers_search($data['customers']->email);
                                            if (isset($customers->id)) {
                                                $cardInfo = self::endpoint([], '/customers/'.$customers->id.'/cards/'.$data['card_id'], 'GET');
                                                if (isset($cardInfo->payment_method)) {
                                                    $array['payment_method_id'] = $cardInfo->payment_method->id;
                                                    $array['issuer_id'] = $cardInfo->issuer->id ?? '';
                                                    $array['payer']['id'] = $customers->id;
                                                    $array['payer']['type'] = 'customer';
                                                    $originalPayer = $array['payer'];
                                                    $array['payer'] = array_merge($originalPayer, [
                                                        'id' => $customers->id,
                                                        'type' => 'customer'
                                                    ]);
                                                    $array['additional_info'] = [
                                                        'items' => [
                                                            [
                                                                'id' => $data['id'],
                                                                'title' => $data['name'],
                                                                'description' => $data['name'],
                                                                'picture_url' => '',
                                                                'category_id' => 'others',
                                                                'quantity' => 1,
                                                                'unit_price' => (float)$data['price']
                                                            ]
                                                        ]
                                                    ];
                                                    // Criar token para cartão salvo
                                                    $tokenData = [
                                                        'card_id' => $data['card_id']
                                                    ];
                                                    $tokenResponse = self::endpoint($tokenData, '/card_tokens', 'POST');
                                                    if (isset($tokenResponse->id)) {
                                                        $array['token'] = $tokenResponse->id;
                                                    }
                                                }
                                            }
                                        }
                                    // CARD_ID

                                    // TOKEN
                                        else {
                                            $array['token'] = $data['card_token'] ?? ''; 
                                            $array['issuer_id'] = $data['card_issuer_id'] ?? '';
                                            $array['payment_method_id'] = $data['card_payment_method_id'] ?? ''; 
                                        }
                                    // TOKEN

                                    $array['installments'] = (isset($data['card_installments']) && $data['card_installments']>1) ? (int)$data['card_installments'] : 1; 
                                    break;
                            // CARD_CREDIT


                            // PIX
                                case 'pix':
                                    $array['payment_method_id'] = 'pix';
                                    $array['date_of_expiration'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (!empty($data['pix_expiration']) ? $data['pix_expiration'] : 1), date('Y')))."T23:59:59.000-03:00";
                                    break;
                            // PIX


                            // BOLETO
                                case 'boleto':
                                    $array['payment_method_id'] = 'bolbradesco';
                                    $array['date_of_expiration'] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + (!empty($data['boleto_expiration']) ? $data['boleto_expiration'] : (XSettings::get__(['pay_boleto_days_expire'])->pay_boleto_days_expire??3)), date('Y')))."T23:59:59.000-03:00";
                                    break;
                            // BOLETO
                        }
                    // PAY_METHOD
                // DATA


                // ENDPOINT
                    return self::endpoint($array, '/payments', 'POST');
                // ENDPOINT
            }
        // CREATE





        // GET
            public static function pay_get(string | int $id): object
            {
                // ENDPOINT
                    return self::endpoint([], '/payments/'.$id, 'GET');
                // ENDPOINT
            }
        // GET
    // PAY










    // CUSTOMERS
        // DATA
            public static function customers_data(object $customers): array
            {
                // CPF_CNPJ
                    if (isset($customers->cpf_cnpj) && $customers->cpf_cnpj) {
                        if (mb_strlen($customers->cpf_cnpj) > 14) {
                            $customers->cnpj = $customers->cpf_cnpj;
                        } else {
                            $customers->cpf = $customers->cpf_cnpj;
                        }
                    }
                // CPF_CNPJ

                $array = [
                    'first_name' => limit1(name($customers->name), 20),
                    'last_name' => limit1(name($customers->name, 1), 20),
                    'email' => LOCALHOST ? 'joaosilva1@silvao.com' : $customers->email,
                    'identification' => [
                        'type' => (isset($customers->cnpj) && $customers->cnpj) ? 'CNPJ' : 'CPF',
                        'number' => (isset($customers->cnpj) && $customers->cnpj) ? number($customers->cnpj) : number($customers->cpf),
                    ],
                    'phone' => [
                        'area_code' => phone_ddd($customers->phone),
                        'number' => phone_number($customers->phone),
                    ],
                    'address' => [
                        'street_name' => not('accents', explode('-', $customers->address)[0]),
                        'street_number' => (int)not('accents', $customers->number),
                        'neighborhood' => not('accents', $customers->neighborhood),
                        'city' => not('accents', $customers->city),
                        'federal_unit' => not('accents', $customers->uf),
                        'zip_code' => not('accents', number($customers->zipcode)),
                    ],
                ];

                return $array;
            }
        // DATA





        // CREATE
            public static function customers(array $customers): object
            {
                unset($customers['email']);
                unset($customers['neighborhood']);
                unset($customers['city']);
                unset($customers['federal_unit']);

                // ENDPOINT
                    return self::endpoint($customers, '/customers', 'POST');
                // ENDPOINT
            }
        // CREATE





        // UPDATE
            public static function customers_update(array $customers, string | int $id): object
            {
                unset($customers['email']);
                unset($customers['neighborhood']);
                unset($customers['city']);
                unset($customers['uf']);

                // ENDPOINT
                    return self::endpoint($customers, '/customers/'.$id, 'PUT');
                // ENDPOINT
            }
        // UPDATE





        // GET
            public static function customers_get(string | int $id): object
            {
                // ENDPOINT
                    return self::endpoint([], '/customers/'.$id, 'GET');
                // ENDPOINT
            }
        // GET





        // SEARCH
            public static function customers_search(string $email): object
            {
                $email = LOCALHOST ? 'teste@mercadopago.com' : $email;

                // ENDPOINT
                    $response = self::endpoint([], '/customers/search?email='.$email , 'GET');
                    return $response->results[0] ?? (object)[];
                // ENDPOINT
            }
        // SEARCH
    // CUSTOMERS











    // CARD
        // CREATE
            public static function card(Request $request): object
            {
                // CUSTOMERS
                    $response = self::customers_search($request->user()->email);

                    if (!isset($response->id)) {
                        $data = $request->user();
                        $customers = self::customers_data($data);
    
                        $response = self::customers($customers);
                    }

                    $customers_id = $response->id;
                // CUSTOMERS


                // TOKEN (token deve vir do frontend)
                    $data = [
                        'token' => $request['token']['token'] ?? ''
                    ];
                // SALVAR CARTAO COM TOKEN


                // ENDPOINT
                    return self::endpoint($data, "/customers/{$customers_id}/cards", 'POST');
                // ENDPOINT
            }
        // CREATE





        // GET
            public static function card_get(Request $request): array
            {
                $customers = self::customers_search($request->user()->email);

                // ENDPOINT
                    if (isset($customers->id)) {
                        $response = self::endpoint([], '/customers/'.$customers->id.'/cards', 'GET');
                    }
                // ENDPOINT

                $return = [];
                if (isset($response->results)) {
                    foreach($response->results as $key => $value) {
                        $return[] = (object)[
                            'id' => $value->id,
                            'brand' => $value->issuer->name,
                            'last_four_digits' => $value->last_four_digits,
                        ];
                    }
                }

                return $return;
            }
        // GET





        // DELETE
            public static function card_delete(Request $request): void
            {
                $customers = self::customers_search($request->user()->email);

                // ENDPOINT
                    if (isset($customers->id)) {
                        self::endpoint([], '/customers/'.$customers->id.'/cards/'.$request['id'], 'DELETE');
                    }
                // ENDPOINT
            }
        // DELETE
    // CARD











    // WEBHOOKS
        public static function webhooks(Request $request): object
        {
            $return = (object)[];

            $id = $request['id'] ?? $request['data_id'] ?? null;
            if ($id) {
                $array = self::pay_get($id);
                if (isset($array->status) && isset($array->id)) {

                    // INFOS
                        $return->id = $array->id;
                        $return->type = $request['type'];
                        $return->gateway = 'MercadoPago';
                        $return->status__ = $array->status;
                    // INFOS
    

                    // METADATA
                        $return->metadata = (object)[];
                        $return->metadata->id = $array->metadata->id;
                        $return->metadata->table = $array->metadata->table;
                        $return->metadata->code = $array->metadata->code;
                    // METADATA


                    // STATUS
                        // PAID
                            if (strtolower($array->status) === 'approved') {
                                $return->status = 10;
                            }
                        // PAID

                        // CANCELLED
                            else if(strtolower($array->status) === 'cancelled') {
                                $return->status = 3;
                            }
                        // CANCELLED

                        // REFUNDED
                            else if(strtolower($array->status) == 'refunded' || strtolower($array->status) == 'charged_back') {
                                $return->status = 4;
                            }
                        // REFUNDED
                    // STATUS


                    // ORDERS
                        __PayService::webhooks($request, $return);
                    // ORDERS


                    // JSON
                        if (!file_exists(DIR_D.'/json/webhooks/')) {
                            mkdir(DIR_D.'/json/webhooks/', 0755, true);
                        }

                        $file = fopen(DIR_D.'/json/webhooks/'.$return->metadata->table.'__'.$return->metadata->id.' - '.date('Y-m-d H.i.s').' - MercadoPago.json', 'w');
                        fwrite($file, json_encode([ 'array' => $array, 'return' => $return, 'request' => $request->all() ]));
                        fclose($file);
                    // JSON

                }
            }

            unset($request['__GLOBAL__']);
            Webhooks::create([
                'type' => 'MercadoPago',
                'status' => isset($return->status) ? $return->status : 0,
                'orders' => isset($return->metadata->id) ? $return->metadata->id : 0,
                'gateway_id' => isset($array->id) ? $array->id : '',
                'gateway_webhooks_id' => isset($array->id) ? $array->id : '',
                'return__' => json_encode($return),
                'reponse' => isset($array->id) ? json_encode($array) : '',
                'request' => json_encode($request->all()),
            ]);

            return $return;
        }
    // WEBHOOKS

}




















/*

    $array = [
        'id' => 123,
        'name' => '',
        'price' => 100.00,

        'code' => '123',
        'table' => 'orders',

        'customers' => (object)[
            'name' => 'João Silva',
            'email' => 'teste@mercadopago.com',
            'cpf' => '12345678900',
            'phone' => '12345678900',
            'address' => 'Rua João Silva',
            'number' => '123',
            'complement' => 'Apto 101',
            'neighborhood' => 'Jardim',
            'city' => 'São Paulo',
            'uf' => 'SP',
            'zipcode' => '123456789',
        ],

        // CARD_CREDIT
            'card_token' => '12345678900',
            'card_issuer_id' => '12345678900',
            'card_payment_method_id' => '12345678900',
            'card_installments' => 1,
        // CARD_CREDIT

        // PIX
            'pix_expiration' => 1,
        // PIX

        // BOLETO
            'boleto_expiration' => 1,
        // BOLETO

    ];





    // ------------------------------------------------------------





    // DATA
        // INFO
            $data = [
                'id' => $arr['Orders']->id,
                'name' => 'Cobranca Numero '.$arr['Orders']->id,
                'price' => price_number($arr['Orders']->total),

                'code' => $arr['Orders']->code,
                'table' => 'orders',
                'customers' => $arr['Orders']->json->CART->users,
            ];

            // ADDRESS
                if ( !(isset($data['city']) AND $data['city']) ) {
                    $data['customers']->address = $arr['Orders']->json->CART->address->address;
                    $data['customers']->number = $arr['Orders']->json->CART->address->number;
                    $data['customers']->complement = $arr['Orders']->json->CART->address->complement;
                    $data['customers']->neighborhood = $arr['Orders']->json->CART->address->neighborhood;
                    $data['customers']->city = $arr['Orders']->json->CART->address->city;
                    $data['customers']->uf = $arr['Orders']->json->CART->address->uf;
                    $data['customers']->zipcode = $arr['Orders']->json->CART->address->zipcode;
                }
            // ADDRESS
        // INFO


        // CARD_CREDIT
            if ($method == 'card_credit') {
                // $data['cards_id'] = $arr['Orders']->json->CART->cards;

                $data['card_token'] = $request['card_token'] ?? '';
                $data['card_issuer_id'] = $request['card_issuer_id'] ?? '';
                $data['card_payment_method_id'] = $request['card_payment_method_id'] ?? '';
                $data['card_installments'] = $request['card_installments'] ?? 1;
            }
        // CARD_CREDIT

        // PIX
            if ($method == 'pix') {
                $data['pix_expiration'] = 1;
            }
        // PIX

        // BOLETO
            if ($method == 'boleto') {
                $data['boleto_expiration'] = $XSettings->pay_boleto_days_expire;
            }
        // BOLETO
    // DATA

    $response = __MercadoPagoService::pay($request, $data, $method);

*/