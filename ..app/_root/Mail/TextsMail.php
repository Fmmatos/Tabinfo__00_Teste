<?php

namespace Root\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Vendor\Models\Texts;

class TextsMail
{
    /*
    *   __Mail::send__($request, [ 'table' => 'customers', 'type' => $request['type'], 'query' => ['customers' => $customers] ]);
    */

    // ID
        public static function id(Request|array $request, array $data): int
        {
            $id = 0;

            // CADASTRO
                if(isset($data['table']) && $data['table'] == 'customers_new'){
                    if($data['type'] == 'customers'){
                        $id = 51;
                    }
                    if($data['type'] == 'approved'){
                        $id = 52;
                    }
                    if($data['type'] == 'unapproved'){
                        $id = 53;
                    }
                    if($data['type'] == 'indications'){
                        $id = 55;
                    }
                }
            // CADASTRO

            // ORDERS
                if(isset($data['table']) && $data['table'] == 'orders'){
                    if($data['type'] == 'new'){
                        $id = 61;
                    }
                    if($data['type'] == 'change_status'){
                        $id = 62;
                    }
                    if($data['type'] == 'tracking'){
                        $id = 63;
                    }
                }
            // ORDERS

            return $id;
        }
    // ID





    // TEXTS
        public static function texts(Request|array $request, array $data, int $id): object
        {
			$return = (object)[];

            // NEW
                // XXX
                    if(isset($data['table']) && $data['table'] == 'xxx'){
                        if($data['type'] == 'change'){
                            $return = Texts::editor()->find($data['query']['xxx']);
                        }
                    }
                // XXX
            // NEW

            if(!isset($return->id) && $id){
                $return = Texts::where('texts.id', $id)->editor()->first();
            }

            return $return;
        }
    // TEXTS





    // VARIABLES
        public static function variables(Request|array $request, object | null $Texts, array $data, object $XSettings): array
        {
            $return  = array();

            // DEFAULT
                $return['logo'] = '<img src="'.DIR.'/img/logo.png" style="max-height: 70px" />';
                $return['nome_do_site'] = $XSettings->name_site;
                $return['dominio'] = '<a href="'.DIR.'">'.DIR.'</a>';

                // CUSTOMERS
                    // NEW
                        $return['nome'] = isset($data['query']['customers']->name) ? fullname($data['query']['customers']->name, 2) : '';
                        $return['email'] = $data['query']['customers']->email ?? '';
                        $return['senha'] = $request['password_temp'] ?? '';

                        $return['id'] = $data['query']['customers']->id ?? '';
                        $return['url'] = $data['query']['customers']->url ?? '';
                        $return['link_indicacao'] = '<a href="'.DIR.'/invitation/'.($return['url'] ? $return['url'] : $return['id']).'">'.DIR.'/invitation/'.($return['url'] ? $return['url'] : $return['id']).'</a>';
                    // NEW


                    // INDICATIONS
                        $return['nome_indicado'] = '';
                        $return['data_cadastro'] = '';
                    // INDICATIONS
                // CUSTOMERS





                // ORDERS
                    // NEW
                        $return['numero_do_pedido'] =isset( $data['query']['orders']->id) ? '#'. $data['query']['orders']->id : '';
                        $return['data_da_compra'] = isset($data['query']['orders']->created_at) ? date__($data['query']['orders']->created_at, 'd/m/Y H:i') : '';
                        $return['valor_total'] = isset($data['query']['orders']->total) ? price($data['query']['orders']->total, 1) : '';
                        $return['forma_de_pagamento'] = isset($data['query']['orders']->pay_method) ? pay_method($data['query']['orders']->pay_method) : '';

                        $return['itens_da_compra'] = '';
                        if(isset($data['query']['orders']->name) && isset($data['query']['orders']->name)){
                            $name = is_json($data['query']['orders']->name) ? json_decode($data['query']['orders']->name) : $data['query']['orders']->name;
                            if(is_array($name)){
                                foreach ($name as $k => $v) {
                                    $return['itens_da_compra'] .= '<div style="line-height: 20px;">';
                                        if(isset($v->name)){
                                            $return['itens_da_compra'] .= ($k+1).'. '.$v->name;
                                        }
                                        if(isset($v->qty) && isset($v->price__)){
                                            $return['itens_da_compra'] .= ' ('.$v->qty.'x '.price($v->price__, 1).')';
                                        }
                                    $return['itens_da_compra'] .= '</div>';
                                }
                            }
                        }
                    // NEW


                    // CHANGE_STATUS
                        $return['status_do_pedido'] = $data['query']['orders_status']->name ?? '';;
                    // CHANGE_STATUS


                    // TRACKING
                        $return['codigo_rastreamento'] = $data['query']['orders']->tracking ?? '';
                        $return['transportadora'] = $data['query']['orders']->shipping_name ?? '';
                        $return['link_rastreamento'] = isset($data['query']['orders']->id) ? '<a href="'.DIR.'/tracking/'.$data['query']['orders']->id.'">Link de Rastreamento</a>' : '';
                    // TRACKING               
                // ORDERS
            // DEFAULT

            return $return;
        }
    // VARIABLES





    // LEGENDS
        public static function legends(Request $request, array $arr, Model $model, object $menu_admin): array
        {
            if($menu_admin->id == 15 && isset($request['GET'][1])){
                $array = [];

                // CUSTOMERS
                    // NEW
                        if($request['GET'][1] == 51){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                        'senha' => 'Senha',
                                    ],
                                ],
                            ];
                        }
                    // NEW


                    // APPROVED
                        if($request['GET'][1] == 52){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                        'link_indicacao' => 'link de indicação',
                                    ],
                                ],
                            ];
                        }
                    // APPROVED


                    // UNAPPROVED
                        if($request['GET'][1] == 53){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                    ],
                                ],
                            ];
                        }
                    // UNAPPROVED


                    // INDICATIONS
                        if($request['GET'][1] == 55){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente Indicado',
                                    'array' => (object)[
                                        'nome_indicado' => 'Nome do Indicado',
                                        'data_cadastro' => 'Data de cadastro do Indicado',
                                    ],
                                ],
                            ];
                        }
                    // INDICATIONS
                // CUSTOMERS





                // ORDERS
                    // NEW
                        if($request['GET'][1] == 61){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Pedido',
                                    'array' => (object)[
                                        'numero_do_pedido' => 'Número do Pedido',
                                        'data_da_compra' => 'Data da compra',
                                        'itens_da_compra' => 'Itens da compra',
                                        'valor_total' => 'Valor total',
                                        'forma_de_pagamento' => 'Forma de pagamento',
                                    ],
                                ],
                            ];
                        }
                    // NEW


                    // CHANGE STATUS
                        if($request['GET'][1] == 62){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Pedido',
                                    'array' => (object)[
                                        'status_do_pedido' => 'Status do pedido',
                                        'numero_do_pedido' => 'Número do Pedido',
                                        'data_da_compra' => 'Data da compra',
                                        'itens_da_compra' => 'Itens da compra',
                                        'valor_total' => 'Valor total',
                                        'forma_de_pagamento' => 'Forma de pagamento',
                                    ],
                                ],
                            ];
                        }
                    // CHANGE STATUS


                    // SHIPPING
                        if($request['GET'][1] == 63){
                            $array = [
                                (object)[
                                    'name' => 'Informações do Site',
                                    'array' => (object)[
                                        'logo' => 'Logo do Site',
                                        'nome_do_site' => 'Nome do Site',
                                        'dominio' => 'Domínio',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Cliente',
                                    'array' => (object)[
                                        'nome' => 'Nome',
                                        'email' => 'Email',
                                        'senha' => 'Senha',
                                    ],
                                ],
                                (object)[
                                    'name' => 'Informações do Pedido',
                                    'array' => (object)[
                                        'codigo_rastreamento' => 'Codigo de rastreamento',
                                        'transportadora' => 'Transportadora',
                                        'link_rastreamento' => 'Link de rastreamento',

                                        'numero_do_pedido' => 'Número do Pedido',
                                        'data_da_compra' => 'Data da compra',
                                        'itens_da_compra' => 'Itens da compra',
                                        'valor_total' => 'Valor total',
                                        'forma_de_pagamento' => 'Forma de pagamento',
                                    ],
                                ],
                            ];
                        }
                    // SHIPPING
                // ORDERS


                $arr['OBJ']['individual']['top_left'] = editor_legend($array);
            }

            return $arr;

        }
    // LEGENDS

}