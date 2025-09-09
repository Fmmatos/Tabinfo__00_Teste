<?php

namespace Vendor\Services\Root;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vendor\Models\CustomersAddress;
use Vendor\Models\CustomersAddress2;
use Vendor\Models\Products;
use Vendor\Services\Root\__ShippingService;

class __CartService
{

    // UPDATE (SAVE / QTY / DELETE / SHIPPING)
        public static function update(Request $request, $array_ini=null, $verify=false): array
        {
            $XSettings = __PayService::config();
            $ar['OBJ']['success'] = null;
            $ar['OBJ']['rejected'] = null;

            $subtotal = 0;
            $array = $array_ini ? $array_ini : self::CART_COOKIES_GET();

            // PRODUCTS
                // SAVE ***
                    $ids = [isset($request['id'])  ? $request['id'] : 0];
                    $items = [];
                    if (isset($array->items)) {
                        try {
                            foreach ($array->items as $key => $value) {
                                if (isset($value->sku)) {
                                    $ex = explode('-', $value->sku);
                                    $ids[] = $ex[0];
                                    $items[$value->sku] = $value;
                                }
                            }
                        } catch (\Throwable $th) { }
                    }


                    // QUERY
                        $query = Products::whereIn('id', $ids);

                        // DELETE ***
                            if ($request['delete']) {
                                $query = $query->where('id', '!=', $request['delete']);
                            }
                        // DELETE ***

                        $products = $query->get();
                    // QUERY


                    $array->items = [];
                    foreach ($products as $key => $value) {
                        $sku = $value->id;

                        $product = $value;
                        $product->sku = $sku;
                        $product->qty = (isset($items[$sku]->qty) && (int)$items[$sku]->qty>0) ? $items[$sku]->qty : 0;

                        // QTY ***
                            if (isset($request['id']) && $request['id'] == $sku) {
                                // ADD QTY
                                    $product->qty = $product->qty + (int)$request['qty'];
                                // ADD QTY

                                // TREATMENT
                                    $product->qty = (int)$product->qty>1 ? $product->qty : 1;
                                    if ($product->qty <= 0) {
                                        $product->qty = 1;
                                    }
                                // TREATMENT
                            }
                        // QTY ***

                        // INFOS
                            $array->items[$sku] = $product;
                            $subtotal += ($value->price__ * $product->qty);
                        // INFOS
                    }
                // SAVE ***
            // PRODUCTS





            // USERS
                $array->users = $request->user();
            // USERS





            // ADDRESS
                $arr['OBJ']['address'] = CustomersAddress::where('customers', $request->user()->id)->get();

                $address = CustomersAddress::where('customers', $request->user()->id)->where('id', (isset($array->address->id) ? $array->address->id : 0))->first();
                if (isset($address->id)) {
                    $array->address = $address;

                } else {
                    $address = CustomersAddress::where('customers', $request->user()->id)->where('main', 1)->first();
                    if (isset($address->id)) {
                        $array->address = $address;

                    } else {
                        $array->address = (object)[];
                    }
                }
            // ADDRESS





            // SHIPPING ***
                $_COOKIE_shipping = COOKIES('CART_SHIPPING');
                if (isset($_COOKIE_shipping->id) && !isset($_GET['SHIPPING_UPDATE']) && !isset($request['shipping']) && !isset($request['shipping_update']) && $verify == false) {
                    $arr['OBJ']['shipping'] = (object)[$_COOKIE_shipping];
                    $arr['OBJ']['shipping_update'] = 1;

                } else {
                    $arr['OBJ']['shipping'] = __ShippingService::index($array);
                }

                $shipping = 0;
                $shipping_temp = isset($array->shipping) ? $array->shipping : (object)[];
                $array->shipping = (object)[];
                if (isset($address->id)) {
                    foreach ($arr['OBJ']['shipping'] as $key => $value) {
                        // NEW
                            if (isset($request['shipping'])) {
                                if (isset($value->id) && $value->id == $request['shipping'] && isset($value->price__)) {
                                    $array->shipping = $value;
                                    $shipping = $value->price__;
                                }
                            }
                        // NEW

                        // OLD
                            else {
                                if (isset($shipping_temp->id) && isset($value->id) && $value->id == $shipping_temp->id && isset($value->price__)) {
                                    $array->shipping = $value;
                                    $shipping = $value->price__;
                                }
                            }
                        // OLD
                    }
                }

                COOKIES_CREATE('CART_SHIPPING', json_encode($array->shipping ?? (object)[]), 30 * 24 * 60 * 60);
            // SHIPPING ***





            // PAY
                if (isset($array->pay->method)) {
                    $method = $array->pay->method;
                }

                $array->pay = __PayService::config(1);

                // METHOD
                    if (isset($method)) {
                        $array->pay->method = $method;
                    }
                // METHOD

                // CARDS
                    $CustomersAddress2 = CustomersAddress2::where('customers', $request->user()->id)->get();
                    $arr['OBJ']['cards'] = [];
                    foreach ($CustomersAddress2 as $key => $value) {
                        $code_1 = crip_2(base64_decode($value->code_1));
                        $ex = explode('|', $code_1);
                        $value->last_four = substr($ex[0], -4);

                        $code_2 = crip_1(base64_decode($value->code_2));
                        $value->brands = $code_2;

                        $value->code_1 = '';
                        $value->code_2 = '';
                        $arr['OBJ']['cards'][] = $value;
                    }

                    $cards = CustomersAddress2::where('id', (isset($array->cards->id) ? $array->cards->id : 0))->first();
                    if (isset($cards->id)) {
                        $cards->code_1 = '';
                        $cards->code_2 = '';
                        $array->cards = $cards;

                    } else {
                        $array->cards = (object)[];
                    }    
                // CARDS
            // PAY





            // COUPONS
                $array = self::coupons($request, $arr, $array, $XSettings);
            // COUPONS





            // INFOS
                // SUBTOTAL
                    $array->subtotal = price($subtotal);
                    $array->subtotal__ = $subtotal;
                // SUBTOTAL


                // DISCOUNT
                    $array = self::discount($request, $arr, $array, $XSettings);
                // DISCOUNT


                // RATES
                    $array = self::rates($request, $arr, $array, $XSettings);
                // RATES


                // FEES
                    $array = self::fees($request, $arr, $array, $XSettings);
                // FEES


                // CREDIT
                    $array = self::credit($request, $arr, $array, $XSettings);
                // CREDIT


                // TOTAL
                    $total = $array->subtotal__ - $array->credit__ - $array->discount__ + $array->rates__ + $array->fees__ + $shipping;

                    $array->total = price($total);
                    $array->total__ = $total;
                // TOTAL


                // INSTALLMENTS
                    $arr = self::installments($request, $arr, $array, $XSettings);
                // INSTALLMENTS
            // INFOS





            // POSITION
                if (!$array_ini) {
                    $array->position = 'address';
                    if (isset($array->address->id) && $array->address->id) {
                        $array->position = 'shipping';
                    }
                    if (isset($array->shipping->price__)) {
                        $array->position = 'pay';

                        if ( !(isset($array->shipping->id) && $array->shipping->id) ) {
                            $array->position = 'shipping';
                        }

                        if ( !(isset($array->address->id) && $array->address->id) ) {
                            $array->position = 'address';
                        }
                    }
                }
            // POSITION


            $arr['OBJ']['CART'] = self::CART_COOKIES_SET($array);


            return [$arr, $array];
        }
    // UPDATE (SAVE / QTY / DELETE / SHIPPING)










    // POSITION
        public static function position(Request $request, string $type): array
        {
            $array = self::CART_COOKIES_GET();

            // ADDRESS
                if ($type == 'address') {
                    $array->address = (object)[];
                    $array->position = 'address';
                    $address = CustomersAddress::where('id', $request['id'])->first();
                    if (isset($address->id)) {
                        $array->address = $address;
                        $array->position = 'shipping';
                    }
                    $_GET['SHIPPING_UPDATE'] = 1;
                }

                if ($type == 'address_reset') {
                    $array->position = 'address';
                    $_GET['SHIPPING_UPDATE'] = 1;
                }
            // ADDRESS


            // SHIPPING
                if ($type == 'shipping_show') {
                    $array->position = 'shipping';
                    $_GET['SHIPPING_UPDATE'] = 1;
                }
            // SHIPPING


            // PAY
                if (compare__('pay_method_', $type)) {
                    $array->position = 'pay';
                    $array->pay->method = replace('pay_method_', '', $type);
                }

                // CARDS
                    if ($type == 'cards') {
                        $array->cards = (object)[];
                        $array->position = 'pay';
                        $cards = CustomersAddress2::where('id', $request['id'])->first();
                        if (isset($cards->id)) {
                            $array->cards = $cards;
                        }
    
                    }
                // CARDS

                // CARD_INSTALLMENTS
                    if ($type == 'card_installments') {
                        $array->position = 'pay';
                        $_GET['SHIPPING_UPDATE'] = 1;
                    }
                    $card_installments = $request['card_installments'] ?? 1;
                // CARD_INSTALLMENTS

            // PAY


            // LIMPANDO REQUEST
                foreach ($request->all() as $key => $value) {
                    unset($request[$key]);
                }
                $request['card_installments'] = $card_installments;
            // LIMPANDO REQUEST

            return self::update($request, $array);
        }
    // POSITION










    // ----------------------------------------------------------------------------------------------------------------------










    // COUPONS
        public static function coupons(Request $request, array $arr, object $array, object $XSettings): object
        {
            $array->credit = 0;
            $array->credit__ = 0;

            return $array;
        }
    // COUPONS










    // DISCOUNT
        public static function discount(Request $request, array $arr, object $array, object $XSettings): object
        {
            $array->discount__ = 0;

            // PAY
                if (isset($array->pay->method) && isset($XSettings->{'pay_discount_'.$array->pay->method}) && $XSettings->{'pay_discount_'.$array->pay->method} > 0) {
                    $val = $XSettings->{'pay_discount_'.$array->pay->method};

                    $val = $val * $array->subtotal__ / 100;

                    $array->discount__ += $val;
                }
            // PAY

            $array->discount = price($array->discount__);
            return $array;
        }
    // DISCOUNT










    // RATES
        public static function rates(Request $request, array $arr, object $array, object $XSettings): object
        {
            $array->rates__ = 0;

            // PAY
                if (isset($array->pay->method) && isset($XSettings->{'pay_rates_'.$array->pay->method}) && $XSettings->{'pay_rates_'.$array->pay->method} > 0) {
                    $val = $XSettings->{'pay_rates_'.$array->pay->method};

                    if ($array->pay->method == 'card_credit') {
                        $val = $val * $array->subtotal__ / 100;
                    }

                    $array->rates__ += $val;
                }
            // PAY

            $array->rates = price($array->rates__);
            return $array;
        }
    // RATES










    // FEES
        public static function fees(Request $request, array $arr, object $array, object $XSettings): object
        {
            $array->fees__ = 0;
            $array->fees__x = 1;

            if (isset($array->pay->method) && $array->pay->method == 'card_credit') {
                $installments = $XSettings->pay_card_credit_installments_max;
                $installments_fees = $XSettings->pay_card_credit_installments_fees;
                $installments_perc = $XSettings->pay_card_credit_installments_interest_perc;

                $y = 1;
                for($i=1; $i <= $installments; $i++) {   
                    if ($installments_fees < $i) {
                        if (isset($request['card_installments']) && $request['card_installments'] == $i) {
                            $array->fees__ += $y*(($installments_perc * $array->subtotal__)/100);
                        }
                        $y++;
                    }
                }
                $array->fees__x = $request['card_installments'] ?? 1;
            }

            $array->fees = price($array->fees__);
            return $array;
        }
    // FEES










    // CREDIT
        public static function credit(Request $request, array $arr, object $array, object $XSettings): object
        {
            $array->credit__ = 0;

            $array->credit = price($array->credit__);
            return $array;
        }
    // CREDIT










    // INSTALLMENTS
        public static function installments(Request $request, array $arr, object $array, object $XSettings): array
        {
            $installments = $XSettings->pay_card_credit_installments_max;
            $installments_fees = $XSettings->pay_card_credit_installments_fees;
            $installments_perc = $XSettings->pay_card_credit_installments_interest_perc;

            $diff = ($array->total__ - $array->fees__) - $array->subtotal__;

            $arr['OBJ']['installments'] = [];

            $y=1;
            for($i=1; $i <= $installments; $i++) {
                $val = $array->subtotal__;
                $fees = 'sem juros';
                $fees__ = 0;
                
                if ($installments_fees < $i) {
                    $fees = 'com juros';
                    $fees__ = $y*(($installments_perc * $array->subtotal__)/100);
                    $val = $array->subtotal__ + $fees__;
                    $y++;
                }

                $val = $val + $diff;

                $arr['OBJ']['installments'][] = (object)[
                    'id' => $i,
                    'name' => $i.'x de '.CURRENCY.' '.price($val/$i).' '.$fees.' ('.CURRENCY.' '.price($val).')',
                    'fees' => $fees__,
                ];
            }

            return $arr;
        }
    // INSTALLMENTS










    // ----------------------------------------------------------------------------------------------------------------------










    // COOKIES
        public static $base64 = 0;
        public static function CART_COOKIES_GET()
        {
            if (COOKIES('CART')) {
                if (self::$base64) {
                    $array = json_decode(base64_decode(COOKIES('CART')));
                } else {
                    $array = json_decode(COOKIES('CART'));
                }
            }

            // TREATMENT
                $return = (object)[];
                if (isset($array->i)) {
                    $return->items = [];
                    foreach ($array->i as $key => $value) {
                        if ($value) {
                            $ex = explode('|', $value);
                            if (isset($ex[1]) && $ex[1]) {
                                $return->items[] = (object)[
                                    'sku' => $ex[0],
                                    'qty' => (int)$ex[1]>1 ? $ex[1] : 1
                                ];
                            }
                        }
                    }
                }
                if (isset($array->a)) {
                    $return->address = (object)[];
                    $return->address->id = $array->a;
                }
                if (isset($array->s)) {
                    $return->shipping = (object)[];
                    $return->shipping->id = $array->s;
                }
                if (isset($array->p)) {
                    $return->pay = (object)[];
                    if ($array->p == 'b') $return->pay->method = 'boleto';
                    if ($array->p == 'p') $return->pay->method = 'pix';
                    if ($array->p == 'c') $return->pay->method = 'card_credit';
                }
                if (isset($array->c)) {
                    $return->cards = (object)[];
                    $return->cards->id = $array->c;
                }
            // TREATMENT

            return $return;
        }

        public static function CART_COOKIES_SET($array)
        {
            // COOKIES
                $cookie = [];
                foreach ($array as $key => $value) {
                    if ($key == 'items') {
                        foreach ($value as $key_1 => $value_1) {
                            if (isset($value_1->sku)) {
                                $temp = $value_1->sku.'|'.((isset($value_1->qty) && (int)$value_1->qty>1) ? $value_1->qty : 1);

                                $cookie['i'][] = $temp;
                            }
                        }

                    } else if($key == 'address') {
                        if (isset($value->id)) {
                            $cookie['a'] = (object)[];
                            $cookie['a'] = $value->id ?? 0;
                        }
                    
                    } else if($key == 'shipping') {
                        $cookie['s'] = $value->id ?? 0;

                    } else if($key == 'pay') {
                        $cookie['p'] = (isset($value->method) && $value->method) ? $value->method[0] : '';

                    } else if($key == 'cards') {
                        $cookie['c'] = $value->id ?? 0;
                    }
                }

                if (self::$base64) {
                    $cookie = base64_encode(json_encode($cookie));
                } else {
                    $cookie = json_encode($cookie);
                }
                COOKIES_CREATE('CART', $cookie, 30 * 24 * 60 * 60);
            // COOKIES

            // RETURN FRONT
                $return = [];

                foreach ($array as $key => $value) {
                    if ($key != 'items') {
                        $return[$key] = $value;

                    } else {
                        foreach ($value as $key_1 => $value_1) {
                            $temp = [];
                            $temp['id'] = $value_1->id;
                            $temp['code'] = $value_1->code;
                            $temp['sku'] = $value_1->sku;
                            $temp['qty'] = (int)$value_1->qty>1 ? $value_1->qty : 1;

                            $temp['name'] = $value_1->name;
                            $temp['image'] = $value_1->image;
                            $temp['image__'] = $value_1->image__;
                            $temp['price'] = $value_1->price;
                            $temp['price__'] = $value_1->price__;
                            $temp['price_1'] = $value_1->price_1;
                            $temp['price_1__'] = $value_1->price_1__;

                            $return[$key][] = $temp;
                        }
                    }
                }

                return $return;
            // RETURN FRONT
        }
    // COOKIES

}