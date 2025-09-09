<?php

namespace Vendor\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Vendor\Models\Customers;

class __Sms
{

    // SEND__
        public static function send(Request|array $request, array $data): int
        {

            // ORDERS
                $customers = Customers::find($data['orders']->customers);
            // ORDERS


            // FROM
                $from = !empty($data['number']) ? $data['number'] : ($customers->twilio_number_phone??'');
            // FROM


            // TO
                if(compare__('-', $data['to'])){
                    GET_pre_fixed_set('error_sms: '.'Número de telefone inválido: ('.replace('-', '', $data['to']).')');
                    return 0;
                }
            // TO


            // DATA
                $postData = [
                    'To' => $data['to'],
                    'From' => $from,
                    'Body' => $data['message']
                ];

                // IMAGEM
                    if(isset($data['file']) AND $data['file']){
                        $postData['MediaUrl'] = $data['file'];
                    }
                // IMAGEM
            // DATA


            // SEND
                $_GET['error_sms'] = '';
                if(LOCALHOST){
                    GET_pre_fixed_set('error_sms: '.$postData);
                    // return 1;
                }

                else {
                    if($data['to'] && $from && isset($customers->twilio_url) && isset($customers->twilio_token)){
                        $response = Http::withHeaders([
                            'Authorization' => 'Basic ' . $customers->twilio_token
                        ])->asForm()->post($customers->twilio_url, $postData);

                       if ($response->successful()) {
                            $array = $response->json();

                        } else {
                            $array = $response->json();
                            GET_pre_fixed_set('error_sms: '.$array);
						}
                    }
                }
            // SEND


            // RETURN
                if(isset($array->account_sid) || isset($array['account_sid'])){
                    return 1;
                } else {
                    return 0;
                }
            // RETURN
        }
    // SEND__

}