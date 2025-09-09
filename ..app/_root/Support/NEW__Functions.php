<?php

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Root\Models\Customers;
use Root\Models\CustomersReviews;
use Root\Models\CustomersStatement;
use Root\Models\CustomersSubscription;
use Root\Models\Levels;

    // README
    // README










    // NEW
    // NEW




















// ----------------------------------------------------------------------------------------------------------------------------------------------------------




















    // DEFINE
        define('TESTE_MAIL', 1);

        define('LOCALHOST_TESTE_MAIL', 0);
        define('LOCALHOST_TESTE_SHIPPING', 1);
        define('LOCALHOST_TESTE_PAY', 0);

        // CURRENCY
            define('CURRENCY', 'R$'); // R$
            define('CURRENCY_DECIMAL', ',00');// ,00
            define('CURRENCY_ACRONYM', 'BRL');// BRL

            define('DATE_FORMAT', 'd/m/Y'); // d/m/Y
            define('DATE_FORMAT_MONTH', 'm/Y'); // m/Y

            define('PHONE_FORMAT', '+55'); // +55
        // CURRENCY
    // DEFINE





    // LIMITS
        define('MAX_UPLOAD_IMAGE', 100 * 1000 * 1000); // IAMGES MAX (JPG, JPEG, PNG, WEB)
        define('MAX_UPLOAD_FILES', 100 * 1000 * 1000); // FILES MAX

        define('MAX_UPLOAD_IMAGE__MAX', 100 * 1000 * 1000); // IMAGE MAX SIZE TO CREATED NEW SMALL
        define('MAX_UPLOAD_IMAGE_LIMIT_WIDHT', 1200); // LIMIT WIDHT IN PX CREATED NEW
    // LIMITS





    // __GLOBAL__
        // TYPES
            // CUSTOMERS
                // ROOT
                    // LOGIN / SIGN-IN / FORGET_PASSWORD
                        $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['LOGIN'] = [
                            'customers' => 'Cliente',
                            // 'users' => 'Usuários',
                        ];
                    // LOGIN / SIGN-IN / FORGET_PASSWORD
                // ROOT


                // DASHBOARD
                    // LOGIN / SIGN-IN / FORGET_PASSWORD
                        $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['DASHBOARD']['LOGIN'] = [
                            'customers' => 'Cliente',
                            // 'users' => 'Usuários',
                        ];
                    // LOGIN / SIGN-IN / FORGET_PASSWORD
                // DASHBOARD
            // CUSTOMERS
        // TYPES










        // THUMBS (table => image => type => place)
            $_GET['__GLOBAL__']['__THUMBS__'] = [
                'default' => ['width' => 800, 'height' => 800],

                // BANNERS
                    'items' => [
                        'image' => [
                            'banners' => [
                                1 => ['width' => 1200, 'height' => 1200],
                            ]
                        ]
                    ],
                // BANNERS

                // USER
                    'products' => [
                        'image' => ['width' => 1000, 'height' => 1000],
                    ],
                // USER

                // USER
                    'customers' => [
                        'image' => ['width' => 150, 'height' => 150],
                    ],
                // USER

            ];
        // THUMBS





        // __THUMBS_FORMATS__ / __MAX_UPLOAD__ => function DEFAULT__DATA
    // __GLOBAL__





    // NEW__DEFAULT__DATA //GLOBAL//
        function NEW__DEFAULT__DATA(array $arr, array | Request $request): array
        {

            // NEW

            // NEW





            // OBJ
                // INFO
                    $arr['OBJ']['info'] = (object)[
    
                        // NEW
                            'pg_login_name' => $_GET['info']->pg_login_name ?? '',
                            'pg_login_description' => $_GET['info']->pg_login_description ?? '',
                            'campanha_description' => nl2br($_GET['info']->campanha_description ?? ''),

                            'image_termo_de_uso_cadastro' => xsetting_image('image_termo_de_uso_cadastro'),
                            'image_termo_de_uso_upload_imagem' => xsetting_image('image_termo_de_uso_upload_imagem'),

                            'pontos' => $_GET['info']->pontos ?? '',
                            'comissao' => $_GET['info']->comissao ?? '',
                            'saque_day' => $_GET['info']->saque_day ?? '',
                            'price_min_saque' => $_GET['info']->price_min_saque ?? '',
                        // NEW


                        // FORMATS
                            'currency' => CURRENCY,
                            'currency_decimal' => CURRENCY_DECIMAL,
                            'currency_acronym' => CURRENCY_ACRONYM,

                            'date_format' => DATE_FORMAT,
                            'date_format_month' => DATE_FORMAT_MONTH,
                            'phone_format' => PHONE_FORMAT,
                        // FORMATS

                        // CONFIG. DO SITE
                            'name_site' => $_GET['info']->name_site ?? '',
                            'image_sharing' => $_GET['info']->image_sharing ?? '',
                        // CONFIG. DO SITE

                        // LOCALIZACAO
                            'google_maps_zoom' => $_GET['info']->google_maps_zoom ?? '',
                            'google_maps_address' => $_GET['info']->google_maps_address ?? '',
                            'google_maps_lat' => $_GET['info']->google_maps_lat ?? '',
                            'google_maps_lng' => $_GET['info']->google_maps_lng ?? '',
                        // LOCALIZACAO

                        // EMAIL
                            'email' => $_GET['info']->email ?? '',
                        // EMAIL

                        // INFORMACOES
                            'address' => $_GET['info']->address ?? '',
                            'phone' => $_GET['info']->phone ?? '',
                            'email_1' => $_GET['info']->email_1 ?? '',
                            'cnpj' => $_GET['info']->cnpj ?? '',
                            'whatsapp' => $_GET['info']->whatsapp ?? '',
                            'whatsapp_code' => $_GET['info']->whatsapp_code ?? '',
                            'whatsapp_txt' => $_GET['info']->whatsapp_txt ?? '',
                            'opening_hours' => $_GET['info']->opening_hours ?? '',
                        // INFORMACOES

                    ];
                // INFO
            // OBJ

            return $arr;
        }

        function xsetting_image(string $key): string
        {
            if(isset($_GET['info']->{$key})){
                $array = json_decode($_GET['info']->{$key});
                if(isset($array[0]->file) && $array[0]->file){
                    return DIR.PHOTOS.'/'.$array[0]->file;
                }
            }
            return '';
        }
    // NEW__DEFAULT__DATA




















// ----------------------------------------------------------------------------------------------------------------------------------------------------------




















    // NEW ADMIN / DASHBOARD
        // CONFIG ADMIN
            // ADMIN__JSON__MENU_ADMIN_BASE64
                define('ADMIN__JSON__MENU_ADMIN_BASE64', 0);
            // ADMIN__JSON__MENU_ADMIN_BASE64

            // LIMITS
                define('ADMIN__MAX_UPLOAD_IMAGE', 100 * 1000 * 1000); // IAMGES MAX (JPG, JPEG, PNG, WEB)
                define('ADMIN__MAX_UPLOAD_FILES', 100 * 1000 * 1000); // FILES MAX

                define('ADMIN__MAX_UPLOAD_IMAGE__MAX', 100 * 1000 * 1000); // IMAGE MAX SIZE TO CREATED NEW SMALL
                define('ADMIN__MAX_UPLOAD_IMAGE_LIMIT_WIDHT', 1200); // LIMIT WIDHT IN PX CREATED NEW
            // LIMITS

            // DATATABLE
                define('ADMIN__DATATABLE__PER_PAGE', 20);
            // DATATABLE
        // CONFIG ADMIN


        // STAR_INFO
            if(!function_exists('star_info')){
                function star_info(object $menu_admin): array
                {
                    $return = [ 'tooltip' => [], 'icons' => [] ];

                    // TOOLTIP
                        $return['tooltip']['star_1'] = 'Destaque';
                        $return['tooltip']['star_2'] = 'Destaque 2';
                        $return['tooltip']['star_3'] = 'Destaque 3';
                        $return['tooltip']['star_4'] = 'Destaque 4';
                        $return['tooltip']['star_5'] = 'Destaque 5';


                        if($menu_admin->table == 'products'){
                            $return['tooltip']['star_1'] = 'Destaque na Home';
                        }
                    // TOOLTIP


                    // ICONS
                        $return['icons']['star_1'] = [
                            'icon' => 'faa-star',
                            'color' => 'c_yellow_1',
                            'bg' => 'b_yellow_2',
                        ];
                        $return['icons']['star_2'] = [
                            'icon' => 'faa-dot-circle-o',
                            'color' => 'c_green',
                            'bg' => 'b_green',
                        ];
                        $return['icons']['star_3'] = [
                            'icon' => 'faa-certificate',
                            'color' => 'c_blue',
                            'bg' => 'b_blue',
                        ];
                        $return['icons']['star_4'] = [
                            'icon' => 'faa-asterisk',
                            'color' => 'c_purple',
                            'bg' => 'b_purple',
                        ];
                        $return['icons']['star_5'] = [
                            'icon' => 'faa-bookmark',
                            'color' => 'c_green_1',
                            'bg' => 'b_green_1',
                        ];
                    // ICONS
            
                    return $return;
                }
            }
        // STAR_INFO
    // NEW ADMIN / DASHBOARD
