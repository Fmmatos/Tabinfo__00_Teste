<?php

namespace Vendor\Services\Root;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Root\Controllers\Dashboard\OrdersDashboardController;
use Vendor\Mail\__Mail;
use Vendor\Models\Customers;
use Vendor\Models\CustomersAddress2;
use Vendor\Models\Orders;
use Vendor\Models\OrdersProducts;
use Vendor\Models\OrdersStatus;
use Vendor\Models\OrdersStatusHistory;

class __OrdersService
{

    // INDEX
        public static function index(Request $request, int $id = 0): JsonResponse
        {
            $arr['OBJ']['orders'] = Orders::where('customers', $request->user()->id)->orderBy('id', 'DESC')->get();
            $arr['OBJ']['orders_status'] = OrdersStatus::orderBy('id', 'DESC')->get()->keyBy('id');

            // STATISTICS
                $arr['OBJ']['orders_statistics'] = @(object)(DB::select("
                    SELECT 
                        c.id AS customer_id,
                        c.name AS customer_name,
                        COUNT(o.id) AS count,
                        SUM(o.total) AS price
                    FROM 
                        customers c
                    LEFT JOIN 
                        orders o ON c.id = o.customers
                    WHERE 
                        o.status >= 10
                    GROUP BY 
                        c.id, c.name
                    ORDER BY 
                        price DESC;
                ")[0]);

                $arr['OBJ']['orders_statistics_all'] = @(object)(DB::select("
                    SELECT 
                        c.id AS customer_id,
                        c.name AS customer_name,
                        COUNT(o.id) AS count,
                        SUM(o.total) AS price
                    FROM 
                        customers c
                    LEFT JOIN 
                        orders o ON c.id = o.customers
                    WHERE 
                        1
                    GROUP BY 
                        c.id, c.name
                    ORDER BY 
                        price DESC;
                ")[0]);
            // STATISTICS

            // JSON
                $id = $id ? $id : $arr['OBJ']['orders'][0]->id ?? null;
                if ($id) {
                    $arr['OBJ']['order'] = Orders::where('customers', $request->user()->id)->find($id);

                    if (isset($arr['OBJ']['order']->id)) {
                        $arr['OBJ']['order'] = json__('/orders/'.$arr['OBJ']['order']->id.'.json', $arr['OBJ']['order']);
                    }
                }
            // JSON

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // INDEX










    // SAVE
        public static function save(Request $request): array
        {
            // CART
                $response = __CartService::update($request, null, true);
                $arr = $response[0];
                $array = $response[1];
            // CART


            // VALIDATE
                $arr = self::validate($arr);
            // VALIDATE


            // SAVE
                if (!isset($arr['errors'])) {
                    if (!isset($arr['alert'])) {

                    // ORDERS
                            $shipping = $array->shipping->price__;
                            $shipping_name = $array->shipping->name;

                            $subtotal = $array->subtotal__;
                            $credit = isset($array->credit__) ? $array->credit__ : 0;
                            $discount = isset($array->discount__) ? $array->discount__ : 0;
                            $rates = isset($array->rates__) ? $array->rates__ : 0;
                            $fees = isset($array->fees__) ? $array->fees__ : 0;
                            $total = $subtotal - $credit - $discount + $rates + $fees + $shipping;

                            $orders_events = null;
                            $orders_events_name = '';

                            $pay_method = $array->pay->method;
                            $installments = $request['card_installments'] ?? 1;
                            $description = $request['description'] ?? '';

                            $pay_last_four = '';
                            $pay_brand = '';
                            $mercado_pago_cards = CustomersAddress2::find($array->cards->id ?? 0);
                            if (isset($mercado_pago_cards->code_1)) {
                                $code_1 = crip_2(base64_decode($mercado_pago_cards->code_1));
                                $code_2 = crip_1(base64_decode($mercado_pago_cards->code_2));
                                $ex = explode('|', $code_1);
                                $pay_last_four = substr($ex[0], -4);
                                $pay_brand = $code_2;
                            }

                            $name = [];
                            foreach ($array->items as $key => $value) {
                                $name[] = [
                                    'name' => $value->name,
                                    'qty' =>  $value->qty,
                                    'price__' =>  $value->price__,
                                ];
                            }

                            $arr['Orders'] = Orders::create([
                                'name' => json_encode($name),
                                'code' => uniqid(),
                                'customers' => $request->user()->id,

                                'status' => 1,
                                'status_date' => date('Y-m-d H:i:s'),
                                
                                'shipping' => $shipping,
                                'shipping_name' => $shipping_name,

                                'subtotal' => $subtotal,
                                'credit' => $credit,
                                'discount' => $discount,
                                'rates' => $rates,
                                'fees' => $fees,
                                'total' => $total,

                                'orders_events' => $orders_events ,
                                'orders_events_name' => $orders_events_name,

                                'pay_method' => $pay_method,
                                'pay_brand' => $pay_brand,
                                'pay_last_four' => $pay_last_four,
                                'installments' => $installments,

                                'description' => $description,

                            ]);

                            // NEW
                                $arr = OrdersDashboardController::save($request, $arr['Orders'], $arr);
                            // NEW
                        // ORDERS





                        // CHANGE_STATUS
                            $request['status'] = 1;
                            $request['user_auto'] = 1;
                            self::change_status($arr['Orders'], $request);
                        // CHANGE_STATUS





                        // ORDERSPRODUCTS
                            foreach ($array->items as $key => $value) {
                                OrdersProducts::create([
                                    'name' => $value->name,
                                    'price' => $value->price__,
                                    'qty' => $value->qty,
                                    'image' => basename($value->image__),

                                    'products' => $value->id,
                                    'orders' => $arr['Orders']->id,
                                ]);
                            }
                        // ORDERSPRODUCTS





                        // JSON
                            $file = fopen(DIR_D.'/json/orders/'.$arr['Orders']->id.'.json', 'w');
                            fwrite($file, json_encode($arr['OBJ']));
                            fclose($file);
                        // JSON





                        // MAIL
                            $customers = Customers::find($arr['Orders']->customers);
                            __Mail::send($request, 'texts', [
                                'table' => 'orders',
                                'type' => 'new',
                                'query' => ['orders' => $arr['Orders'], 'customers' => $customers]
                            ]);
                        // MAIL

                    }
                }
            // SAVE

            return $arr;

        }
    // SAVE




















    // DEFAULT
        // VALIDATE
            public static function validate($arr)
            {
                // ADDRESS
                    if ( !(isset($arr['OBJ']['CART']['address']->id)) ) {
                        $arr['alert'] = 0;
                        $arr['msg'] = 'Selecione o Endereço Entrega da Loja';
                    }
                // ADDRESS


                // SHIPPING
                    else if( !(isset($arr['OBJ']['CART']['shipping']->id) && isset($arr['OBJ']['CART']['shipping']->price__)) ) {
                        $arr['alert'] = 0;
                        $arr['msg'] = 'Selecione o Frete';
                    }
                // SHIPPING


                // ITEMS
                    else if( !(isset($arr['OBJ']['CART']['items']) && $arr['OBJ']['CART']['items']) ) {
                        $arr['alert'] = 0;
                        $arr['msg'] = 'Seu Carrinho está Vazio!';
                    }
                // ITEMS


                return $arr;
            }
        // VALIDATE










        // CHANGE_STATUS
            public static function change_status(object $orders, Request | array $request): bool
            {

                Orders::find_id($orders->id)->update($request);

                if (isset($request['status'])) {
                    $request['status'] = (int)$request['status'];
                    if ($request['status'] > 0) {
                    
                        // ORDERS
                            Orders::find_id($orders->id)->update([
                                'status' => $request['status'],
                                'status_date' => date('Y-m-d H:i:s'),
                                'status_users' => (Auth::check() && Auth::user()->id!=1 && !isset($request['user_auto'])) ? Auth::user()->id : 0,
                                'status_users_ip' => is_array($request) ? '' : ($request->ip()??''),
                                'status_description_customer' => $request['status_description_customer'] ?? '',
                            ]);

                            // PAID
                                if ($orders->paid == 0 && $request['status']==10) {
                                    Orders::find_id($orders->id)->update([
                                        'paid' =>  1,
                                        'date_paid' => date('Y-m-d H:i:s'),
                                        'refund' =>  0,
                                    ]);

                                    OrdersDashboardController::paid($request, $orders);
                                }
                            // PAID

                            // REFUND
                                if ($orders->paid && $orders->refund == 0 && $request['status']==4) {
                                    Orders::find_id($orders->id)->update([
                                        'refund' =>  1,
                                        'date_refund' => date('Y-m-d H:i:s'),
                                        'paid' =>  0,
                                    ]);

                                    OrdersDashboardController::refund($request, $orders);
                                }
                            // REFUND
                        // ORDERS

                        // ORDERSSTATUSHISTORY
                            OrdersStatusHistory::create([
                                'orders' => $orders->id,
                                'status' => $request['status'],
                                'status_date' => date('Y-m-d H:i:s'),
                                'status_users' => (Auth::check() && Auth::user()->id!=1 && !isset($request['user_auto'])) ? Auth::user()->id : 0,
                                'status_users_ip' => is_array($request) ? '' : ($request->ip()??''),
                                'status_description_admin' => $request['status_description_admin'] ?? '',
                                'status_description_customer' => $request['status_description_customer'] ?? '',
                            ]);
                        // ORDERSSTATUSHISTORY


                        // MAIL
                            if ($request['status'] > 1) {
                                $orders = Orders::find($orders->id);
                                $orders_status = OrdersStatus::find($request['status']);
                                $customers = Customers::find($orders->customers);
                                __Mail::send($request, 'texts', [
                                    'table' => 'orders',
                                    'type' => 'change_status',
                                    'query' => ['orders' => $orders, 'orders_status' => $orders_status, 'customers' => $customers]
                                ]);
                            }
                        // MAIL

                    }
                }

                return true;
            }
        // CHANGE_STATUS
    // DEFAULT

}