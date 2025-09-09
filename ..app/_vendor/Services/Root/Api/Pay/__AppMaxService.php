<?php

/*
* https://docs.appmax.com.br/api/
* https://docs.appmax.com.br/webhooks/
*/

namespace Vendor\Services\Root\Api\Pay;

use Illuminate\Http\Request;
use Root\Models\CustomersSubscription;
use Vendor\Models\CustomersAddress2;
use Vendor\Models\Webhooks;
use Vendor\Models\XSettings;
use Vendor\Services\Root\__CartService;
use Vendor\Services\Root\__PayService;
use Vendor\Traits\Root\Pay\__AppMaxTrait;

class __AppMaxService
{
    use __AppMaxTrait;

    // PAY
        // CREATE
            public static function pay(Request $request, array $data, string $method, ?object $class = null): object
            {
                // CUSTOMER
                    $data['customers']->email = LOCALHOST ? 'joaosilva1@silvao.com' : $data['customers']->email;

                    $customers = self::customers_create_or_update($data['customers']);
                    if (isset($customers->error)) {
                        return $customers;
                    }
                    if (!isset($customers->id)) {
                        return (object)['error' => 'Ocorreu um erro ao criar ou atualizar cliente, tente novamente mais tarde!'];
                    }
                // CUSTOMER


                // CREATE ORDER
                    $orderData = [
                        'customer_id' => $customers->id,
                        'products' => [
                            [
                                'sku' => $data['id'],
                                'name' => $data['name'] ?? 'Pagamento',
                                'qty' => 1,
                            ]
                        ],
                        'total' => (float)$data['price'],
                        'discount' => 0,
                        'shipping' => 0,
                        'freight_type' => '',
                    ];

                    $order = self::endpoint($orderData, '/order', 'POST');
                    if (isset($order->error)) {
                        return $order;
                    }
                    if (!isset($order->id)) {
                        return (object)['error' => 'Ocorreu um erro ao criar pedido, tente novamente mais tarde!'];
                    }
                // CREATE ORDER


                // PAY
                    $paymentData = [
                        'cart' => [ 'order_id' => $order->id ],
                        'customer' => [ 'customer_id' => $customers->id ],
                        // 'metadata' => [
                        //     'id' => $data['id'],
                        //     'table' => $data['table'],
                        //     'code' => $data['code'],
                        // ],
                    ];

                    // METHOD
                        switch ($method) {
                            // CARD_CREDIT
                                case 'card_credit':
                                    // CARD_ID (saved card)
                                        // if (!empty($data['card_id'])) {
                                        //     $cardInfo = self::card_get_by_id($customers->id, $data['card_id']);
                                        //     if (isset($cardInfo->token)) {
                                        //         $paymentData['card_token'] = $cardInfo->token;
                                        //         $paymentData['card_cvv'] = $data['card_cvv'] ?? '';
                                        //     } else {
                                        //         return (object)['error' => 'Cartão salvo não encontrado'];
                                        //     }
                                        // }
                                    // CARD_ID

                                    // NEW CARD
                                        // else {
                                            // if (!empty($data['card_token'])) {
                                            //     $paymentData['payment']['CreditCard']['card_token'] = $data['card_token'];
                                            // }

                                            // else if (!empty($data['card_number'])) {

                                                // CART
                                                    if(!isset($data['card_number'])){
                                                        $response = __CartService::update($request, null, true);

                                                        if (isset($response[0]['OBJ']['CART']['cards']->id)) {
                                                            $mercado_pago_cards = CustomersAddress2::find($response[0]['OBJ']['CART']['cards']->id);
                                        
                                                            if (isset($mercado_pago_cards->code_1)) {
                                                                $code_1 = crip_2(base64_decode($mercado_pago_cards->code_1));
                                                                $ex = explode('|', $code_1);
                                                                $data['card_number'] = $ex[0];
                                                                $data['card_month'] = $ex[1];
                                                                $data['card_year'] = $ex[2];

                                                                $data['card_name'] = $mercado_pago_cards->name;
                                                                $data['card_cvv'] = $request['card_cvv'];
                                                            }

                                                        } else {
                                                            return (object)['error' => 'Cartão não encotnrado, tente outra cartão!'];
                                                        }
                                                    }
                                                // CART


                                                // TREATMENT
                                                    $card_number = preg_replace('/\D/', '', $data['card_number']);
                                                    $card_holder_name = limit1(trim(not('accents', $data['card_name'] ?? $data['customers']->name, ' ')), 64);
                                                    $card_cvv = $data['card_cvv'];
                                                    // $card_brand = card_brand($data['card_number']);

                                                    $expiration_date = [];
                                                    if(isset($data['card_month']) && isset($data['card_year'])){
                                                        $expiration_date = explode('/', $data['card_month'].'/'.$data['card_year']);
                                                    }
                                                    if(isset($data['expiration_date'])){
                                                        $expiration_date = explode('/', $data['expiration_date']);
                                                    }
                                                    $expiration_date[0] = (int)$expiration_date[0];
                                                    $expiration_date[1] = isset($expiration_date[1]) ? (int)substr((int)$expiration_date[1] >= 2000 ? (int)$expiration_date[1] : 2000 + (int)$expiration_date[1], 2) : '';
                                                // TREATMENT


                                                // VALIDADE
                                                    if(!$card_holder_name || strlen($card_holder_name) < 5){
                                                        return (object)['error' => 'Nome do titular do cartão inválido, verifique se o nome está correto'];
                                                    }
                                                    if(!is_card_number($card_number)){
                                                        return (object)['error' => 'Número de cartão inválido, verifique se o número está correto'];
                                                    }
                                                    if(!is_card_expiration_date($expiration_date)){
                                                        return (object)['error' => 'Data de validade do cartão inválida, verifique se o mês e ano estão corretos'];
                                                    }
                                                    if(!$card_cvv || strlen($card_cvv) < 2){
                                                        return (object)['error' => 'Código de segurança do cartão inválido'];
                                                    }
                                                    // if(!$card_brand){
                                                    //     return (object)['error' => 'Não aceitamos esta bandeira de cartão, verifique se o número do cartão está correto'];
                                                    // }
                                                // VALIDADE

                                                $paymentData['payment']['CreditCard'] = [
                                                    'name' => $card_holder_name,
                                                    'number' => $card_number,
                                                    'month' => $expiration_date[0],
                                                    'year' => $expiration_date[1],
                                                    'cvv' => $card_cvv,
                                                    // 'brand' => $card_brand,
                                                ];

                                                // $tokenResponse = self::endpoint($tokenData, '/tokenize/card', 'POST');
                                                // if (isset($tokenResponse->token)) {
                                                //     $paymentData['card_token'] = $tokenResponse->token;
                                                    
                                                //     if (!empty($data['save_card'])) {
                                                //         self::card_save($customers->id, $tokenResponse->token, $data);
                                                //     }

                                                // } else {
                                                //     return (object)['error' => 'Erro ao tokenizar cartão: ' . ($tokenResponse->error ?? 'Token inválido')];
                                                // }
                                        //     }
                                        // }
                                    // NEW CARD

                                    $paymentData['payment']['CreditCard']['document_number'] = $customers->custom_txt;
                                    $paymentData['payment']['CreditCard']['installments'] = (isset($data['card_installments']) && $data['card_installments'] > 1) ? (int)$data['card_installments'] : 1;
                                    $paymentData['payment']['CreditCard']['soft_descriptor'] = limit1(trim(not('accents', XSettings::get__(['name_site'])->name_site, '')), 13);
                                    
                                    $endpoint = '/payment/credit-card';
                                    break;
                            // CARD_CREDIT


                            // PIX
                                case 'pix':
                                    $paymentData['payment']['pix']['document_number'] = $customers->custom_txt;
                                    $paymentData['payment']['pix']['expiration_date'] = date('Y-m-d H:i:s', strtotime("+1 days"));
                                    
                                    $endpoint = '/payment/pix';
                                    break;
                            // PIX


                            // BOLETO
                                case 'boleto':
                                    $paymentData['payment']['Boleto']['document_number'] = $customers->custom_txt;
                                    
                                    $endpoint = '/payment/boleto';
                                    break;
                            // BOLETO

                            default:
                                return (object)['error' => 'Método de pagamento inválido: ' . $method];
                        }
                    // METHOD

                    // ENDPOINT
                        // if($method == 'card_credit'){
                            // $response = (object)['sucess' => true];

                        // } else {
                            $response = self::endpoint($paymentData, $endpoint, 'POST');
                        // }

                        if($class){
                            // APROVED
                                if (isset($response->sucess) && $response->sucess) {
                                    // CARD_CREDIT
                                        if ($method == 'card_credit') {
                                            $class->update([
                                                'status' => 2, // Em Analise
                                                'gateway' => 'AppMax',
                                                'gateway_id' => (string)$order->id,
                                                'gateway_status' => $response->status??null,
                                                'pay_last_four' => isset($card_number) ? substr($card_number, -4) : null,
                                            ]);
                                        }
                                    // CARD_CREDIT


                                    // PIX
                                        if ($method == 'pix') {
                                            $class->update([
                                                'gateway' => 'AppMax',
                                                'gateway_id' => (string)$order->id,
                                                'gateway_pix_qrcode' => $response->pix_emv ?? null,
                                                'gateway_pix_qrcode_base64' => $response->pix_qrcode ?? null,
                                                'gateway_pix_qrcode_url' => $response->pix_qrcode_url ?? null,
                                                'gateway_pix_qrcode_expiration' => $response->pix_expiration_date ?? null,
                                            ]);
                                        }
                                    // PIX

                                    if ($method == 'boleto') {
                                        $class->update([
                                            'gateway' => 'AppMax',
                                            'gateway_id' => (string)$order->id,
                                            'gateway_boleto_url' => $response->pdf ?? null,
                                            'gateway_boleto_barcode' => $response->digitable_line ?? $response->boleto_payment_code ?? null,
                                            'gateway_boleto_expiration' => $response->due_date ?? null,
                                        ]);
                                    }
                                
                                }
                            // APROVED

                            // ELSE
                                else {
                                    // CARD_CREDIT
                                        if($method == 'card_credit'){
                                            $class->update([
                                                'status' => 5, // Reprovado
                                                'gateway' => 'AppMax',
                                                'gateway_id' => (string)$order->id,
                                                'gateway_status' => $response->status??null,
                                                'pay_last_four' => isset($card_number) ? substr($card_number, -4) : null,
                                            ]);
                                        }
                                    // CARD_CREDIT
                                }
                            // ELSE
                        // ENDPOINT

                    } else {
                            $response->id = (string)$order->id;
                            $response->status = $array->status??null;

                            // APROVED
                                if (isset($response->sucess) && $response->sucess) {
                                    // CARD_CREDIT
                                        if ($method == 'card_credit') {
                                        }
                                    // CARD_CREDIT


                                    // PIX
                                        if ($method == 'pix') {
                                            $response->point_of_interaction = (object)[];
                                            $response->point_of_interaction->transaction_data = (object)[];
                                            $response->point_of_interaction->transaction_data->qr_code = $response->pix_emv??null;
                                        }
                                    // PIX

                                    if ($method == 'boleto') {
                                        $response->transaction_details = (object)[];
                                        $response->transaction_details->external_resource_url = $response->pdf ?? null;
                                        $response->barcode = (object)[];
                                        $response->barcode->content = $response->digitable_line ?? $response->boleto_payment_code ?? null;
                                        $response->date_of_expiration = $response->due_date ?? null;
                                    }
                                
                                }
                            // APROVED
                        // ENDPOINT
                    }
                // PAY

                return $response;
            }
        // CREATE
    // PAY










    // CUSTOMERS
        // DATA FORMAT
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
                    'firstname' => limit1(name($customers->name), 100),
                    'lastname' => limit1(name($customers->name, 1), 100),
                    'email' => $customers->email,
                    'telephone' => phone_complete($customers->phone),
                    'custom_txt' => (isset($customers->cnpj) && $customers->cnpj) ? $customers->cnpj : $customers->cpf,

                    'address_street' => not('accents', explode('-', $customers->address)[0]),
                    'address_street_number' => (int)not('accents', $customers->number),
                    'address_street_complement' => not('accents', $customers->complement??''),
                    'address_street_district' => not('accents', $customers->neighborhood),
                    'address_city' => not('accents', $customers->city),
                    'address_state' => not('accents', $customers->uf),
                    'postcode' => not('accents', number($customers->zipcode)),
                ];

                return $array;
            }
        // DATA FORMAT


        // CREATE OR UPDATE
            public static function customers_create_or_update(object $customers): object
            {
                $query = self::customers_search($customers->email ?? '');

                // CREATE
                    if (isset($query->id)) {
                        $customerData = self::customers_data($customers);
                        return self::customers_update($customerData, $query->id);
                    }
                // CREATE

                // UPDATE
                    else {
                        $customerData = self::customers_data($customers);
                        return self::customers($customerData);
                    }
                // UPDATE
            }
        // CREATE OR UPDATE



        // CREATE
            public static function customers(array $customers): object
            {
                // ENDPOINT
                    return self::endpoint($customers, '/customer', 'POST');
                // ENDPOINT
            }
        // CREATE



        // UPDATE
            public static function customers_update(array $customers, string | int $id): object
            {
                // ENDPOINT
                    return self::endpoint($customers, '/customer/' . $id, 'PUT');
                // ENDPOINT
            }
        // UPDATE



        // GET
            public static function customers_get(string | int $id): object
            {
                // ENDPOINT
                    return self::endpoint([], '/customer/' . $id, 'GET');
                // ENDPOINT
            }
        // GET



        // SEARCH
            public static function customers_search(string $email): object
            {
                $email = $email;

                // ENDPOINT
                    $response = self::endpoint([], '/customer?email=' . urlencode($email), 'GET');
                    if(isset($response->data) && is_array($response->data)){
                        foreach($response->data as $key => $value) {
                            if($value->email == $email) {
                                return (object)$value;
                            }
                        }
                    }

                    return (object)[];
                // ENDPOINT
            }
        // SEARCH
    // CUSTOMERS










    // ORDERS
       // GET
            public static function pay_orders(string | int $id): object
            {
                // ENDPOINT
                    return self::endpoint([], '/order/' . $id, 'GET');
                // ENDPOINT
            }
        // GET


        // STATUS
            public static function pay_orders_status(object $orders): string
            {
                $status = strtolower((string)($orders->status ?? ''));
                if (!empty($orders->refunded_at) || (float)($orders->total_refunded ?? 0) > 0 || $status === 'estornado') {
                    return 'refunded';
                }
                if (($orders->payment_type ?? null) === 'CreditCard' && !empty($orders->paid_at) && in_array($status, ['pago','aprovado','integrado'], true)) {
                    return 'approved';
                }
                return empty($orders->paid_at) || in_array($status, ['reprovado','recusado','cancelado'], true) ? 'not_approved' : 'pending';
            }
        // STATUS
    // ORDERS










    // CARD
        // SAVE
            public static function cards_save(int $customer_id, string $token, array $data): object
            {
                $cardData = [
                    'customer_id' => $customer_id,
                    'token' => $token,
                    'card_holder_name' => $data['card_name'] ?? '',
                    'card_last_digits' => substr(preg_replace('/\D/', '', $data['card_number'] ?? ''), -4),
                    'card_brand' => card_brand($data['card_number'] ?? ''),
                ];

                // ENDPOINT
                    return self::endpoint($cardData, '/customer/' . $customer_id . '/card', 'POST');
                // ENDPOINT
            }
        // SAVE



        // CREATE (from request)
            public static function cards(Request $request): object
            {
                // CUSTOMERS
                    $response = self::customers_search($request->user()->email);

                    if (!isset($response->id)) {
                        $data = $request->user();
                        $customers = self::customers_data($data);
                        $response = self::customers($customers);
                    }

                    if (isset($response->error)) {
                        return $response;
                    }

                    $customer_id = $response->id;
                // CUSTOMERS


                // TOKENIZE CARD
                    $tokenData = [
                        'card_number' => preg_replace('/\D/', '', $request['card_number'] ?? ''),
                        'card_holder_name' => $request['card_name'] ?? $request->user()->name,
                        'card_expiration_month' => str_pad($request['card_month'], 2, '0', STR_PAD_LEFT),
                        'card_expiration_year' => strlen($request['card_year']) == 2 ? '20' . $request['card_year'] : $request['card_year'],
                        'card_cvv' => $request['card_cvv'],
                        'card_brand' => card_brand($request['card_number']),
                    ];
                    
                    $tokenResponse = self::endpoint($tokenData, '/tokenize/card', 'POST');
                    
                    if (isset($tokenResponse->error)) {
                        return $tokenResponse;
                    }
                // TOKENIZE CARD


                // SAVE CARD
                    $cardData = [
                        'customer_id' => $customer_id,
                        'token' => $tokenResponse->token,
                        'card_holder_name' => $request['card_name'] ?? $request->user()->name,
                        'card_last_digits' => substr(preg_replace('/\D/', '', $request['card_number']), -4),
                        'card_brand' => card_brand($request['card_number']),
                    ];

                    return self::endpoint($cardData, '/customer/' . $customer_id . '/card', 'POST');
                // SAVE CARD
            }
        // CREATE



        // GET ALL CARDS
            public static function cards_get(Request $request): array
            {
                $customers = self::customers_search($request->user()->email);

                $return = [];
                
                // ENDPOINT
                    if (isset($customers->id)) {
                        $response = self::endpoint([], '/customer/' . $customers->id . '/cards', 'GET');
                        
                        if (isset($response->results) && is_array($response->results)) {
                            foreach($response->results as $key => $value) {
                                $return[] = (object)[
                                    'id' => $value->id,
                                    'brand' => $value->card_brand ?? 'unknown',
                                    'last_four_digits' => $value->card_last_digits ?? $value->last_four_digits ?? '****',
                                    'holder_name' => $value->card_holder_name ?? '',
                                ];
                            }
                        } else if (isset($response->cards) && is_array($response->cards)) {
                            foreach($response->cards as $key => $value) {
                                $return[] = (object)[
                                    'id' => $value->id,
                                    'brand' => $value->brand ?? 'unknown',
                                    'last_four_digits' => $value->last_digits ?? '****',
                                    'holder_name' => $value->holder_name ?? '',
                                ];
                            }
                        }
                    }
                // ENDPOINT

                return $return;
            }
        // GET ALL CARDS



        // GET SINGLE CARD
            public static function cards_get_by_id(int $customer_id, string $card_id): object
            {
                // ENDPOINT
                    $response = self::endpoint([], '/customer/' . $customer_id . '/card/' . $card_id, 'GET');
                    return $response;
                // ENDPOINT
            }
        // GET SINGLE CARD



        // DELETE
            public static function cards_delete(Request $request): void
            {
                $customers = self::customers_search($request->user()->email);

                // ENDPOINT
                    if (isset($customers->id) && isset($request['id'])) {
                        self::endpoint([], '/customer/' . $customers->id . '/card/' . $request['id'], 'DELETE');
                    }
                // ENDPOINT
            }
        // DELETE
    // CARD










    // REFUND
        public static function refund(string | int $order_id, ?float $amount = null, ?string $reason = null): object
        {
            $data = [
                'order_id' => $order_id,
                'reason' => $reason ?: 'Solicitação de reembolso',
            ];

            // Partial refund
            if ($amount !== null && $amount > 0) {
                $data['type'] = 'partial';
                $data['amount'] = $amount;
            } 
            // Total refund
            else {
                $data['type'] = 'total';
            }

            // ENDPOINT
                return self::endpoint($data, '/refund', 'POST');
            // ENDPOINT
        }
    // REFUND










    // WEBHOOKS
        public static function webhooks(Request $request): object
        {
            $return = (object)[];
            $status = 0;

            $id = $request['id'] ?? $request['data']['id'] ?? null;
            if ($id) {
                $orders = self::pay_orders($id);

                if(isset($orders->id)){
                    $return->id = $orders->id;

                    // APPROVED
                        if(self::pay_orders_status($orders) === 'approved') {
                            $status = 10;
                            $return->status = 'approved';
                            CustomersSubscription::select(['id'])->where('gateway', 'AppMax')->where('gateway_id', $orders->id)->update([
                                'status' => 10,
                                'expires_at' => date('Y-m-d H:i:s', strtotime("+1 year")),
                            ]);
                        }
                    // APPROVED


                    // REFUNDED
                        else if (self::pay_orders_status($orders) === 'refunded') {
                            $status = 4;
                            $return->status = 'refunded';
                            CustomersSubscription::select(['id'])->where('gateway', 'AppMax')->where('gateway_id', $orders->id)->update([
                                'status' => 4,
                                'expires_at' => null,
                            ]);
                        }
                    // REFUNDED
                }
                else {
                    $return->error = 'Pedido não encontrado';
                    $status = 0;
                }
            }

            // SAVE WEBHOOK LOG
                unset($request['__GLOBAL__']);
                Webhooks::create([
                    'type' => 'AppMax',
                    'status' => $status,
                    'orders' => $id,
                    'gateway_id' => $request['data']['id']??null,
                    'gateway_webhooks_id' => isset($data['webhook_id']) ? $data['webhook_id'] : '',
                    'return__' => json_encode($return),
                    'reponse' => isset($orders) ? json_encode($orders) : '',
                    'request' => json_encode($request->all()),
                ]);
            // SAVE WEBHOOK LOG

            return $return;
        }
    // WEBHOOKS










    // TRACKING
        public static function add_tracking_code(int $order_id, string $tracking_code, string $carrier = ''): object
        {
            $data = [
                'order_id' => $order_id,
                'tracking_code' => $tracking_code,
                'carrier' => $carrier ?: 'Correios',
            ];

            // ENDPOINT
                return self::endpoint($data, '/order/delivery-tracking-code', 'POST');
            // ENDPOINT
        }
    // TRACKING

}









/*
EXAMPLE USAGE:

    $array = [
        'id' => 123,
        'name' => 'Produto Teste',
        'price' => 100.00,

        'code' => '123',
        'table' => 'orders',

        'customers' => (object)[
            'name' => 'João Silva',
            'email' => 'teste@appmax.com',
            'cpf' => '12345678900',
            'phone' => '11999998888',
            'address' => 'Rua João Silva',
            'number' => '123',
            'complement' => 'Apto 101',
            'neighborhood' => 'Jardim',
            'city' => 'São Paulo',
            'uf' => 'SP',
            'zipcode' => '01234567',
        ],

        // CARD_CREDIT
            'card_number' => '4111111111111111',
            'card_name' => 'JOAO SILVA',
            'card_month' => '12',
            'card_year' => '25',
            'card_cvv' => '123',
            'card_installments' => 1,
            'save_card' => true, // Optional: save card for future use
        // CARD_CREDIT

        // OR USE SAVED CARD
            'card_id' => '123456', // Saved card ID
            'card_cvv' => '123',   // CVV still required for saved cards
        // OR USE SAVED CARD

        // PIX
            'pix_expiration' => 1, // Days until expiration
        // PIX

        // BOLETO
            'boleto_expiration' => 3, // Days until expiration
        // BOLETO
    ];


    // ------------------------------------------------------------


    // USE IN CONTROLLER:

    // DATA
        $data = [
            'id' => $arr['Orders']->id,
            'name' => 'Pedido #'.$arr['Orders']->id,
            'price' => price_number($arr['Orders']->total),

            'code' => $arr['Orders']->code,
            'table' => 'orders',
            'customers' => $arr['Orders']->json->CART->users,
        ];

        // ADDRESS
            if (!(isset($data['customers']->city) AND $data['customers']->city)) {
                $data['customers']->address = $arr['Orders']->json->CART->address->address;
                $data['customers']->number = $arr['Orders']->json->CART->address->number;
                $data['customers']->complement = $arr['Orders']->json->CART->address->complement;
                $data['customers']->neighborhood = $arr['Orders']->json->CART->address->neighborhood;
                $data['customers']->city = $arr['Orders']->json->CART->address->city;
                $data['customers']->uf = $arr['Orders']->json->CART->address->uf;
                $data['customers']->zipcode = $arr['Orders']->json->CART->address->zipcode;
            }
        // ADDRESS


        // CARD_CREDIT
            if ($method == 'card_credit') {
                // New card
                $data['card_number'] = $request['card_number'] ?? '';
                $data['card_name'] = $request['card_name'] ?? '';
                $data['card_month'] = $request['card_month'] ?? '';
                $data['card_year'] = $request['card_year'] ?? '';
                $data['card_cvv'] = $request['card_cvv'] ?? '';
                $data['card_installments'] = $request['card_installments'] ?? 1;
                $data['save_card'] = $request['save_card'] ?? false;
                
                // Or saved card
                // $data['card_id'] = $request['card_id'] ?? '';
                // $data['card_cvv'] = $request['card_cvv'] ?? '';
            }
        // CARD_CREDIT

        // PIX
            if ($method == 'pix') {
                $data['pix_expiration'] = 1;
            }
        // PIX

        // BOLETO
            if ($method == 'boleto') {
                $data['boleto_expiration'] = $XSettings->pay_boleto_days_expire ?? 3;
            }
        // BOLETO
    // DATA

    $response = __AppMaxService::pay($request, $data, $method);

    if (isset($response->error)) {
        // Handle error
        return response()->json(['error' => $response->error], 400);
    }

    // Success - save payment info to order
    $arr['Orders']->gateway = 'AppMax';
    $arr['Orders']->gateway_id = $response->id ?? $response->order_id ?? '';
    $arr['Orders']->save();

    // Return response with payment details
    return response()->json($response);

*/