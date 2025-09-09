<?php

namespace Vendor\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Vendor\Mail\__Mail;
use Vendor\Models\Admin\YMenuAdmin_Admin;
use Vendor\Models\Customers;
use Vendor\Models\Orders;
use Vendor\Models\OrdersStatusHistory;
use Vendor\Models\XSettings;
use Vendor\Resources\__Resource;
use Vendor\Services\Root\__OrdersService;

class __UseFullService
{


    // AUTOCOMPLETE
        static public function autocomplete(Request $request): JsonResponse
        {
            $menu_admin = YMenuAdmin_Admin::json($request['module']);
            $table = $request['table'];
            $busca = $request['busca'];
            $arr = __Resource::autocomplete($request, $menu_admin, $table, $busca);

            $arr['status'] = 200;
            return json_encode__($arr);
        }
    // AUTOCOMPLETE





    // CITY
        static public function city(Request $request): JsonResponse
        {
            $arr = [];
            $city = [];
            
            if (isset($request['uf']) && $request['uf']) {
                $file = DIR_D.'/json/locations/city/'.$request['uf'].'.json';
                if (file_exists($file)) {
                    $array = json_decode(file_get_contents($file));
                    if (isset($array[0]->cidades)) {
                        $city = [];
                        foreach ($array[0]->cidades as $key => $value) {
                            $city[$key] = (object)array();
                            $city[$key]->id = $key;
                            $city[$key]->name = $value;
                        }
                    }
                }    
            }
            $arr['OBJ']['city'] = $city;

            $arr['status'] = 200;
            return json_encode__($arr);
        }
    // CITY





    // ZIPCODE
        static public function zipcode(Request $request): JsonResponse
        {
            $arr = [];

            if (isset($request['zipcode']) && $request['zipcode']) {
                $arr = array('errors'=>1, 'address'=>'', 'neighborhood'=>'', 'city'=>'', 'uf'=>'');
                $zipcode = replace('.', '', $request['zipcode']);
                $zipcode = replace('-', '', $zipcode);

                // LOCAL
                    $file = DIR_D.'/json/locations/zipcode/'.$zipcode[0].'0000000.json';
                    if (file_exists($file)) {
                        $array = json_decode(file_get_contents($file), 1);
                        foreach ($array as $key => $value) {
                            if (isset($value[$zipcode])) {
                                $data = $value[$zipcode];
                                if (isset($data['estados'])) {
                                    $arr = array('errors'=>0, 'address'=>$data['rua'], 'neighborhood'=>$data['bairros'], 'city'=>$data['cidades'], 'uf'=>$data['estados']);
                                }
                            }
                        }
                    }
                // LOCAL


                // VIACEP
                    if (isset($arr['errors']) && $arr['errors'] == 1) {
                        $response = Http::get('https://viacep.com.br/ws/'.$zipcode.'/json/');
                        $data = $response->json();

                        if (isset($data['cep'])) {
                            $arr = array('errors'=>0, 'address'=>$data['logradouro'], 'neighborhood'=>$data['bairro'], 'city'=>$data['localidade'], 'uf'=>$data['uf']);
                        }
                }
                // VIACEP


                // GOOGLE
                    if (isset($arr['errors']) && $arr['errors'] == 1) {
                        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?address='.$zipcode.'&key='.XSettings::get__(['key_google'])->key_google);
                        $data = $response->json();

                        if (isset($data['results'][0]['address_components'])) {
                            foreach ($data['results'][0]['address_components'] as $key => $value) {
                                foreach ($value as $key_1 => $value_1) {
                                    if ($key_1 == 'types') {
                                        foreach ($value_1 as $key_2 => $value_2) {
                                            if ($value_2 == 'route') {
                                                $arr['address'] = $value['long_name'];
                                            //} else if ($value_2 == 'street_number') {
                                                //$arr['number'] = $value['long_name'];
                                            } else if ($value_2 == 'neighborhood' || $value_2 == 'sublocality_level_1') {
                                                $arr['neighborhood'] = $value['long_name'];
                                            } else if ($value_2 == 'locality' || $value_2 == 'administrative_area_level_2') {
                                                $arr['city'] = $value['long_name'];
                                            } else if ($value_2 == 'administrative_area_level_1') {
                                                $arr['uf'] = $value['short_name'];
                                            //} else if ($value_2 == 'postal_code') {
                                                //$arr['postal_code'] = $value['long_name'];
                                            //} else if ($value_2 == 'postal_code_prefix') {
                                                //$arr['postal_code_prefix'] = $value['long_name'];;
                                            }

                                            $arr['errors'] = 0;
                                        }
                                    }
                                }
                            }
                        }
                    }
                // GOOGLE


                // ERRO
                    if (isset($arr['errors']) && $arr['errors'] == 1) {
                        $arr = [];
                        $arr['errors'][] = 'CEP Não Encontrado!';
                    }
                // ERRO
            }

            $arr['status'] = 200;
            return json_encode__($arr);
        }
    // ZIPCODE





    // QRCODE
        // php/qrcode/
            public static function qrcode__(string $txt): string
            {
                return DIR.'/qrcode?txt='.$txt;
            }
        // php/qrcode/


        // QrCode
            public static function qrcode(Request $request, string $text, int $size = 300, bool $url = true): string|object
            {
                $size = $size>1000 ? 300 : $size;
                $text = replace('____BARRA____', '/', $text);

                if(is_base64($text)) {
                    $text = base64_decode($text);
                }

                $qrCode  = QrCode::size($size)->generate($text);

                if ($url) {
                    return $qrCode;

                } else {
                    return response($qrCode)->header('Content-Type', 'image/svg+xml');
                }
            }
        // QrCode
    // QRCODE





    // ORDERS_ACTIONS
        public static function orders_actions(Request $request, string $type): JsonResponse
        {
            $arr = [];

            DB::beginTransaction();
            try {

                // STATUS
                    if ($type == 'status') {

                        // VALIDATE
                            $orders = Orders::find($request['id']);

                            // PAID
                                if ($orders->paid && $request['status'] <= 10 && $request['status'] != 4) {
                                    $arr['errors'][] = 'O pedido foi pago!';
                                }
                            // PAID

                            // REFUND
                                if ($orders->refund && $request['status']==4) {
                                    $arr['errors'][] = 'O pedido foi foi estornado!';

                                } else if($orders->paid == 0 && $request['status']==4) {
                                    $arr['errors'][] = 'O pedido não foi pago!';
                                }
                            // REFUND
                        // VALIDATE


                        if (!isset($arr['errors'])) {
                            __OrdersService::change_status($orders, $request);
                        }

                    }
                // STATUS


                // STATUS_DELETE
                    if ($type == 'status_delete') {

                        // VALIDATE
                            $orders = Orders::find($request['order']);
                            $ordersStatusHistory = OrdersStatusHistory::where('orders', $request['order'])->find($request['id']);

                            // PAID
                                if ($orders->paid && $ordersStatusHistory->status == 10) {
                                    $arr['errors'][] = 'O pedido foi pago! Não pode apagar este status!';
                                }
                            // PAID

                            // REFUND
                                if ($orders->refund && $ordersStatusHistory->status == 4) {
                                    $arr['errors'][] = 'O pedido foi estornado! Não pode apagar este status!';
                                }
                            // REFUND
                        // VALIDATE


                        if (!isset($arr['errors'])) {
                            // ACTIONS
                                OrdersStatusHistory::where('orders', $request['order'])->where('id', $request['id'])->delete();
                                if ($request['key'] === "0") {
                                    $ordersStatusHistory = OrdersStatusHistory::where('orders', $request['order'])->orderBy('id', 'DESC')->first();

                                    Orders::find_id($request['order'])->update([
                                        'status' => $ordersStatusHistory->status ?? 1,
                                        'status_date' => $ordersStatusHistory->status_date ?? date('Y-m-d H:i:s'),
                                        'status_users' => $ordersStatusHistory->status_users ?? 0,
                                        'status_users_ip' => $ordersStatusHistory->status_users_ip,
                                        'status_description_customer' => $ordersStatusHistory->status_description_customer ?? '',
                                    ]);

                                    if (!isset($ordersStatusHistory->status)) {
                                        OrdersStatusHistory::create([
                                            'orders' => $request['order'],
                                            'status' => 1,
                                            'status_date' => date('Y-m-d H:i:s'),
                                            'status_users' => Auth::user()->id!=1 ? Auth::user()->id : 0,
                                            'status_users_ip' => $request->ip()??'',
                                        ]);        
                                    }
                                }
                            // ACTIONS
                        }

                    }
                // STATUS_DELETE


                // TRACKING
                    if ($type == 'tracking') {

                        // ACTIONS
                    Orders::find_id($request['id'])->update([
                        'tracking' => $request['tracking'],
                    ]);
                        // ACTIONS

                        // MAIL
                            $orders = Orders::find($request['id']);
                            $customers = Customers::find($orders->customers);
                            __Mail::send($request, 'texts', [
                                'table' => 'orders',
                                'type' => 'tracking',
                                'query' => ['orders' => $orders, 'customers' => $customers]
                            ]);
                        // MAIL

                    }
                // TRACKING

                $arr['status'] = 200;
                DB::commit();

            } catch (\Throwable $th) {
                DB::rollBack();
                $arr = errors__($th, $arr);
            }

            return json_encode__($arr, $request);
        }
    // ORDERS_ACTIONS





    // DOWNLOAD
        public function download(string $file, string $name='arquivo'): string
        {
            ob_start(); if (!isset($no_session_start)) { session_start(); }
            $_SESSION['download'] = 'ok';
            $url = DIR.'/php/download.php?file='.$file.'&name='.$name;
            return '<script>window.open("'.$url.'", "_blank"); window.history.back();</script>';
        }
    // DOWNLOAD





    // EXPORT
        public function export(Request $request, string $type): void
        {
            // EXCEL
                if ($type == 'excel') {
                    __ExportService::excel($request);
                }
            // EXCEL


            // PDF
                else if($type == 'pdf') {
                    __ExportService::pdf($request);
                }
            // PDF
        }
    // EXPORT





    // CAPTURE
        public function capture(Request $request)
        {
            $headers = getallheaders();
            if (isset($headers['Authorization']) && $headers['Authorization'] == 'Bearer MOIFD89J8MNWEQ987JDQDQ8W9JN7887WH2J0E9JHWDKPED90') {
                return __UploadService::upload_capture($request);

            } else {
                echo "Unauthorized";
            }            
        }
    // CAPTURE





    // REFRESH_BD_LOCAL
        public static function refresh_bd_local(Request $request): JsonResponse
        {
            $arr = [];

            if(LOCALHOST) {

                // BACKUP BD LOCAL
                    $arr['BACKUP_BD_LOCAL'] = backup__BD_LOCAL();
                // BACKUP BD LOCAL

                // BACKUP BD PRODUCTION (DOWNLOAD)
                    $arr['BACKUP_BD_PRODUCTION'] = import__BD_FROM_DOWNLOADS();
                // BACKUP BD PRODUCTION (DOWNLOAD)

                // DROP BD LOCAL
                    $arr['DROP_BD_LOCAL'] = drop__BD_LOCAL();
                // DROP BD LOCAL

                // IMPORT BD PRODUCTION TO LOCAL
                    // $arr['IMPORT_BD_PRODUCTION_TO_LOCAL'] = import__BD_PRODUCTION_TO_LOCAL($arr);
                // IMPORT BD PRODUCTION TO LOCAL
            }

            $arr['status'] = 200;
            return json_encode__($arr);
        }
    // REFRESH_BD_LOCAL
}