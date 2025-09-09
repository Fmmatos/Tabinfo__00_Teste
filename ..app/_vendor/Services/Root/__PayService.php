<?php

namespace Vendor\Services\Root;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vendor\Models\CustomersAddress2;
use Vendor\Models\Orders;
use Vendor\Models\XSettings;
use Vendor\Services\Root\Api\Pay\__MercadoPagoService;

class __PayService
{

    // CART
        // PAY
            public static function cart_pay(Request $request, string $method): JsonResponse
            {
                $arr = [];
                $XSettings = __PayService::config();

                DB::beginTransaction();
                try {                

                    // ORDER
                        $arr = __OrdersService::save($request);
                    // ORDER





                    // PAY
                        if (LOCALHOST && LOCALHOST_TESTE_PAY) {
                            $arr['alerts'] = 1;
                            $arr['status'] = 200;


                        } else if(isset($arr['Orders']->id)) {
                            $arr['Orders'] = json__('/orders/'.$arr['Orders']->id.'.json', $arr['Orders']);
                            
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
                                        if ( !(isset($data['city']) && $data['city']) ) {
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
                                        $data['customers'] = $arr['Orders']->json->CART->cards;

                                        $data['card_token'] = $request['card_token'] ?? '';
                                        $data['card_issuer_id'] = $request['card_issuer_id'] ?? '';
                                        $data['card_payment_method_id'] = $request['card_payment_method_id'] ?? '';
                                        $data['card_installments'] = $request['card_installments'] ?? 1;
                                    }
                                // CARD_CREDIT

                                // BOLETO
                                    if ($method == 'boleto') {
                                        $data['boleto_expiration'] = $XSettings->pay_boleto_days_expire;
                                    }
                                // BOLETO

                                // PIX
                                    if ($method == 'pix') {
                                        $data['pix_expiration'] = 1;
                                    }
                                // PIX
                            // DATA


                            // GATEWAYS
                                $pays_ended = 0;
                                $rejected_ended = 0;
                                $attempts = [];
                                foreach ($XSettings->$method as $key => $value) {
                                    if ($pays_ended == 0) {
                                        if (class_exists('Vendor\Services\Root\Api\Pay\__'.$value.'Service')) {

                                            // PAY
                                                $query = new ('Vendor\Services\Root\Api\Pay\__'.$value.'Service')();
                                                $array = $query->pay($request, $data, $method);
                                            // PAY

                                            // RETURN
                                                // ERROR
                                                    if (isset($array->error)) {
                                                        $error = 'Pagamento não realizado! '.$array->error;
                                                        $arr['error_pay'][] = $error;
                                                        $attempts[] = $value.': '.$error;
                                                    }
                                                // ERROR

                                                // OK
                                                    else {
                                                        // CARD_CREDIT
                                                            if ($method == 'card_credit') {
                                                                if (isset($array->status) && strtolower($array->status) == 'approved') {
                                                                    $response = __OrdersService::change_status($arr['Orders'], [
                                                                        'status' => 2,
                                                                        'pay_method' => $method,
                                                                        'gateway' => $value,
                                                                        'gateway_id' => $array->id,
                                                                        'gateway_status' => $array->status,
                                                                    ]);
                                                                    if ($response) { $pays_ended = 1; }

                                                                } else if(isset($array->status) && strtolower($array->status) == 'rejected') {
                                                                    $response = __OrdersService::change_status($arr['Orders'], [
                                                                        'status' => 5,
                                                                        'pay_method' => $method,
                                                                        'gateway' => $value,
                                                                        'gateway_id' => $array->id,
                                                                        'gateway_status' => $array->status,
                                                                        'pay_last_four' => $array->card->last_four_digits ?? '',
                                                                    ]);
                                                                    $error = 'Pagamento recusado pela operadora do cartão!';
                                                                    $attempts[] = $value.': '.$error;
                                                                    $rejected_ended = 1;

                                                                } else {
                                                                    $response = __OrdersService::change_status($arr['Orders'], [
                                                                        'status' => 3,
                                                                        'pay_method' => $method,
                                                                        'gateway' => $value,
                                                                        'gateway_id' => $array->id,
                                                                        'gateway_status' => $array->status,
                                                                        'pay_last_four' => $array->card->last_four_digits ?? '',
                                                                    ]);
                                                                    $error = 'Pagamento recusado pela operadora do cartão!';
                                                                    $attempts[] = $value.': '.$error;
                                                                    $rejected_ended = 1;
                                                                }                                            
                                                            }
                                                        // CARD_CREDIT


                                                        // PIX
                                                            if ($method == 'pix') {
                                                                if (isset($array->point_of_interaction->transaction_data->qr_code)) {
                                                                    $response = __OrdersService::change_status($arr['Orders'], [
                                                                        'pay_method' => $method,
                                                                        'gateway' => $value,
                                                                        'gateway_id' => $array->id,
                                                                        'gateway_status' => $array->status,
                                                                        'gateway_pix_qrcode' => $array->point_of_interaction->transaction_data->qr_code,
                                                                    ]);
                                                                    if ($response) { $pays_ended = 1; }

                                                                } else {
                                                                    $response = __OrdersService::change_status($arr['Orders'], [
                                                                        'status' => 3,
                                                                        'pay_method' => $method,
                                                                        'gateway' => $value,
                                                                        'gateway_id' => $array->id,
                                                                        'gateway_status' => $array->status,
                                                                        'pay_last_four' => $array->card->last_four_digits ?? '',
                                                                    ]);
                                                                    $error = 'Erro ao gerar o qrcode! Tente novamente!';
                                                                    $arr['error_pay'][] = $error;
                                                                    $attempts[] = $value.': '.$error;        
                                                                }
                                                            }
                                                        // PIX


                                                        // BOLETO
                                                        if ($method == 'boleto') {
                                                            if (isset($array->barcode->content)) {
                                                                $response = __OrdersService::change_status($arr['Orders'], [
                                                                    'pay_method' => $method,
                                                                    'gateway' => $value,
                                                                    'gateway_id' => $array->id,
                                                                    'gateway_status' => $array->status,
                                                                    'gateway_boleto_url' => $array->transaction_details->external_resource_url ?? null,
                                                                    'gateway_boleto_barcode' => $array->barcode->content,
                                                                    'gateway_boleto_expiration' => date('Y-m-d H:i:s', strtotime($array->date_of_expiration)),
                                                                ]);
                                                                if ($response) { $pays_ended = 1; }

                                                            } else {
                                                                $response = __OrdersService::change_status($arr['Orders'], [
                                                                    'status' => 3,
                                                                    'pay_method' => $method,
                                                                    'gateway' => $value,
                                                                    'gateway_id' => $array->id,
                                                                    'gateway_status' => $array->status,
                                                                    'pay_last_four' => $array->card->last_four_digits ?? '',
                                                                ]);
                                                                $error = 'Erro ao gerar o boleto! Tente novamente!';
                                                                $arr['error_pay'][] = $error;
                                                                $attempts[] = $value.': '.$error;        
                                                            }
                                                        }
                                                    // BOLETO
                                                }
                                                // OK
                                            // RETURN
                                        }
                                    }
                                }

                                // ERROR
                                    if (isset($arr['error_pay'][0])) {
                                        $arr['error'][] = $arr['error_pay'][0];
                                    }
                                // ERROR


                                // ATTEMPTS
                                    if ($attempts) {
                                        Orders::find_id($arr['Orders']->id)->update([
                                            'attempts' => json_encode($attempts),
                                        ]);
                                    }
                                // ATTEMPTS

                                if ($pays_ended) {
                                    $arr['url'] = '/success/'.$arr['Orders']->id;
                                    $arr['status'] = 200;
                                    if ($pays_ended && !PROG) {
                                        COOKIES_DELETE('CART');
                                    }
                                    
                                }
                                if ($rejected_ended) {
                                    $arr['url'] = '/rejected/'.$arr['Orders']->id;
                                    $arr['status'] = 200;
                                }
                            // GATEWAYS
                        }
                    // PAY
    
                    DB::commit();

                } catch (\Throwable $th) {
                    DB::rollBack();
                    $arr = errors__($th, $arr) ?? [];
                }

                if (!LOCALHOST) {
                    unset($arr['Orders']);
                }

                return json_encode__($arr, $request);
            }
        // PAY





        // JS
            public static function cart_pay_js(Request $request): JsonResponse
            {
                $arr['OBJ']['mercado_pago_public_key'] = XSettings::get__(['mercado_pago_public_key'])->mercado_pago_public_key;


                // CART
                    if ($request['type'] == 'cart') {
                        $response = __CartService::update($request, null, true);

                        if (isset($response[0]['OBJ']['CART']['cards']->id)) {
                            $mercado_pago_cards = CustomersAddress2::find($response[0]['OBJ']['CART']['cards']->id);
        
                            if (isset($mercado_pago_cards->code_1)) {
                                $arr['OBJ']['mercado_pago_cards'] = $mercado_pago_cards;
        
                                $arr['OBJ']['mercado_pago_cards']->code_1 = crip_2(base64_decode($mercado_pago_cards->code_1));
                                $arr['OBJ']['mercado_pago_cards']->code_2 = ''; //crip_1(base64_decode($mercado_pago_cards->code_2));
        
                                if (mb_strlen($arr['OBJ']['mercado_pago_cards']->cpf_cnpj) > 14) {
                                    $arr['OBJ']['mercado_pago_cards']->doc_type = 'cnpj';
                                } else {
                                    $arr['OBJ']['mercado_pago_cards']->doc_type = 'cpf';
                                }
                                $arr['OBJ']['mercado_pago_cards']->doc_number = number($arr['OBJ']['mercado_pago_cards']->cpf_cnpj);
                            }
                        }        
                    }
                // CART


                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // JS





        // SUCCESS
            public static function cart_success(Request $request, int $id): JsonResponse
            {
                $Orders = Orders::where('customers', $request->user()->id)->find($id);

                $arr['OBJ']['success'] = $Orders;

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // SUCCESS





        // REJECTED
            public static function cart_rejected(Request $request, int $id): JsonResponse
            {
                $Orders = Orders::where('customers', $request->user()->id)->find($id);

                $arr['OBJ']['rejected'] = $Orders;

                $arr['status'] = 200;
                return json_encode__($arr, $request);
            }
        // REJECTED
    // CART




















    // DEFAULT
        // CONFIG
            public static function config(int $init = 0): object
            {
                $return = (object)[];

                $XSettings = XSettings::get__([
                    'pay_card_credit_1', 'pay_card_credit_2', 'pay_card_credit_3',
                    'pay_boleto_1', 'pay_boleto_2', 'pay_boleto_3',
                    'pay_pix_1', 'pay_pix_2', 'pay_pix_3',

                    'pagarme_client_id', 'pagarme_client_secret',
                    'pagseguro_token', 'pagseguro_environment', 'pagseguro_public_key',
                    'mercado_pago_access_token', 'mercado_pago_public_key',
                    'appmax_token',

                    'pay_boleto_days_expire',
                    'pay_card_credit_installments_max', 'pay_card_credit_installments_fees', 'pay_card_credit_installments_interest_perc',
                    'pay_rates_card_credit', 'pay_rates_pix', 'pay_rates_boleto',
                    'pay_discount_card_credit', 'pay_discount_pix', 'pay_discount_boleto',
                ]);

                // ORDER GATEWAYS
                    $return->boleto = [];
                    if ($XSettings->pay_boleto_1) { $return->boleto[1] = $XSettings->pay_boleto_1; }
                    if ($XSettings->pay_boleto_2) { $return->boleto[2] = $XSettings->pay_boleto_2; }
                    if ($XSettings->pay_boleto_3) { $return->boleto[3] = $XSettings->pay_boleto_3; }

                    $return->pix = [];
                    if ($XSettings->pay_pix_1) { $return->pix[1] = $XSettings->pay_pix_1; }
                    if ($XSettings->pay_pix_2) { $return->pix[2] = $XSettings->pay_pix_2; }
                    if ($XSettings->pay_pix_3) { $return->pix[3] = $XSettings->pay_pix_3; }

                    $return->card_credit = [];
                    if ($XSettings->pay_card_credit_1) { $return->card_credit[1] = $XSettings->pay_card_credit_1; }
                    if ($XSettings->pay_card_credit_2) { $return->card_credit[2] = $XSettings->pay_card_credit_2; }
                    if ($XSettings->pay_card_credit_3) { $return->card_credit[3] = $XSettings->pay_card_credit_3; }


                    // MERCADOPAGO
                        if ($XSettings->mercado_pago_public_key && $XSettings->mercado_pago_access_token) {
                            $return->MercadoPago = [];
                            $return->MercadoPago['class'] = new __MercadoPagoService();
                        }
                    // MERCADOPAGO


                    // APPMAX
                        if ($XSettings->appmax_token) {
                            // $return->AppMax['class'] = new __AppMaxxService();
                        }
                    // APPMAX


                    // PAGARME
                        if ($XSettings->pagarme_client_id && $XSettings->pagarme_client_secret) {
                            // $return->Pagarme['class'] = new __PagarmeService();
                        }
                    // PAGARME


                    // PAGSEGURO
                        if ($XSettings->pagseguro_token && $XSettings->pagseguro_environment && $XSettings->pagseguro_public_key) {
                            // $return->PagSeguro['class'] = new __PagSeguroService();
                        }
                    // PAGSEGURO
                // ORDER GATEWAYS

                
                // INIT
                    $return_ini = (object)[
                        'pay_boleto_days_expire' => $XSettings->pay_boleto_days_expire,
                        'pay_card_credit_installments_max' => $XSettings->pay_card_credit_installments_max,
                        'pay_card_credit_installments_fees' => $XSettings->pay_card_credit_installments_fees,
                        'pay_card_credit_installments_interest_perc' => $XSettings->pay_card_credit_installments_interest_perc,

                        'pay_rates_card_credit' => $XSettings->pay_rates_card_credit,
                        'pay_rates_pix' => $XSettings->pay_rates_pix,
                        'pay_rates_boleto' => $XSettings->pay_rates_boleto,

                        'pay_discount_card_credit' => $XSettings->pay_discount_card_credit,
                        'pay_discount_pix' => $XSettings->pay_discount_pix,
                        'pay_discount_boleto' => $XSettings->pay_discount_boleto,
                    ];

                    if ($init) {
                        $return_ini->boleto = count($return->boleto)>0 ? 1 : 0;
                        $return_ini->pix = count($return->pix)>0 ? 1 : 0;
                        $return_ini->card_credit = count($return->card_credit)>0 ? 1 : 0;

                    } else {
                        foreach ($return_ini as $key => $value) {
                            $return->$key = $value;
                        }
                    }
                // INIT

                return $return;

            }
        // CONFIG










        // WEBHOOKS
            public static function webhooks(Request $request, object $return): void
            {

                // PAYMENT
                    // if ($return->type == 'payment') {

                        // ORDERS
                            if ($return->metadata->table == 'orders') {
                                $orders = Orders::where('code', $return->metadata->code)->where('gateway_id', $return->id)->find($return->metadata->id);

                                if (isset($orders->id)) {
                                    $ok = 0;
                                    if ($orders->paid == 0) {
                                        $ok = 1;
                                    }
                                    if ($orders->paid && $orders->refund == 0 && $return->status == 4) {
                                        $ok = 1;
                                    }

                                    if ($ok && $orders->status != $return->status) {
                                        __OrdersService::change_status($orders, [
                                            'status' => $return->status,
                                        ]);
                                    }
                                }

                            }
                        // ORDERS

                    // }
                // PAYMENT

            }
        // WEBHOOKS
    // DEFAULT

}