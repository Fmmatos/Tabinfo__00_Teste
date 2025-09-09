<?php

use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use PhpParser\Node\Expr\FuncCall;
use Vendor\Models\Admin\YMenuAdmin_Admin;
use Vendor\Models\Admin\YMenuAdminCategories_Admin;
use Vendor\Models\Customers;
use Vendor\Models\ProductsCategories;
use Vendor\Models\Users;
use Vendor\Resources\__Resource;

    // DEFAULT
        // DEFINED
            if (isset($_SERVER['HTTP_HOST'])) {
                // ..PUBLIC_HTML
                    function public_html__xxx(string $path): string
                    {
                        $dir_root = explode('/', __DIR__);

                        $public_html = 'public_html';
                        if (isset($dir_root[2])) {
                            $ex = explode($dir_root[2], str_replace('.', '', $_SERVER['HTTP_HOST']));
                            if ($ex[0]) {
                                $public_html = $ex[0];
                            }
                        }

                        $path = preg_replace('/'.$public_html.'(?!.*'.$public_html.')/', $public_html, $path);
                        return $path;
                    }
                // ..PUBLIC_HTML


                // LOCALHOST
                    define('LOCALHOST', ($_SERVER['HTTP_HOST'] == 'localhost:4000' || stripos($_SERVER['HTTP_HOST'], 'localhost:808') !== false) ? 1 : 0);
                // LOCALHOST


                // DIR
                    $HOST = LOCALHOST ? 'http://localhost:4000' : 'https://'.$_SERVER['HTTP_HOST'];
                    define('HOST', $HOST);

                    $DIR_1 = explode('/public_html/', $_SERVER['SCRIPT_NAME']);
                    $DIR_2 = explode('/index.php', $DIR_1[0]);
                    $DIR__ = isset($DIR_1[1]) ? $DIR_2[0].'/public_html' : $DIR_2[0];
                    define('DIR', (HOST.$DIR__)); // https://domino/Sites/...
                    define('DIR_API', (DIR.'/api'));
                    define('DIR_LINK', (isset($_SERVER['HTTP_ORIGIN']) && stripos($_SERVER['HTTP_ORIGIN'], 'localhost:808') !== false) ? $_SERVER['HTTP_ORIGIN'] : DIR); // https://domino/Sites/... ou  https://localhost/ (vue)
                // DIR


                // DIR_F
                    $DIR_F = explode(DIRECTORY_SEPARATOR.'..app'.DIRECTORY_SEPARATOR, __DIR__)[0];
                    define('DIR_F', ($DIR_F.DIRECTORY_SEPARATOR.'..app')); // FISICO
                    define('DIR_D', ($DIR_F.DIRECTORY_SEPARATOR.'..data')); // FISICO
                    define('DIR_PP', ($DIR_F.DIRECTORY_SEPARATOR.'..public_html')); // FISICO
                    define('DIR_P', public_html__xxx(LOCALHOST ? $DIR_F.DIRECTORY_SEPARATOR.'public_html' : $_SERVER['DOCUMENT_ROOT'].$DIR__)); // FISICO

                    // $_GET['pre'] = $_SERVER;
                    // echo '<pre>';print_r(DIR);echo '</pre>'; echo '<pre>';print_r(DIR_API);echo '</pre>'; echo '<pre>';print_r(DIR_P__);echo '</pre>'; echo '<pre>';print_r(DIR_F);echo '</pre>'; echo '<pre>';print_r(DIR_D);echo '</pre>'; echo '<pre>';print_r(DIR_P);echo '</pre>'; echo '<pre>';print_r('---------------');echo '</pre>';
                // DIR_F

               
                // OTHERS
                    define('PHOTOS', '/web/photos');
                    define('THUMBS', '/web/thumbs');

                    define('DATA_INI', '1990-01-01');

                    // define('SSL', LOCALHOST ? ['verify' => 'D:\wamp64\bin\php\php8.3.14\extras\ssl\cacert.pem'] : []);
                // OTHERS                
            }
        // DEFINED


        // DEFAULT__DATA //GLOBAL//
            if (!function_exists('DEFAULT__DATA')) {
                function DEFAULT__DATA(array $arr, array|Request $request): array
                {
                    // DASHBOARD
                        if (isset($request['GET'][0]) && $request['GET'][0] == 'dashboard') {
                            // PERMISSIONS
                                if (!PERMISSIONS($request)) {
                                    $arr = [];
                                    $arr['error'][] = 'Página não encontrada...';
                                }
                            // PERMISSIONS
                        }
                    // DASHBOARD


                    // ARR
                        if (isset($request->user()->id)) {
                            Customers::find_id($request->user()->id)->update([
                                'last_acess' => date('Y-m-d H:i:s')
                            ]);

                            $arr['OBJ']['user'] = Customers::find($request->user()->id);
                        }

                        if (pages_sign($request)) {
                            $arr['OBJ']['user'] = [];
                        }
                    // ARR


                    // NEW
                        $arr = NEW__DEFAULT__DATA($arr, $request);
                    // NEW

                    return $arr;
                }
            }
        // DEFAULT__DATA


        // LUGAR
            if (!function_exists('LUGAR_ADMIN')) {
                function LUGAR_ADMIN(): bool
                {
                    $return = false;
                    if (isset($_SERVER['REQUEST_URI'])) {
                        if (stripos($_SERVER['REQUEST_URI'], '/admin/') !== false) {
                            $return = true;
                        }
                        if (stripos($_SERVER['REQUEST_URI'], '/menu_admin/') !== false) {
                            $return = true;
                        }
                    }
                    return $return;
                }
            }
        // LUGAR


        // __GLOBAL__ //GLOBAL//
            if (!function_exists('__GLOBAL__')) {
                function __GLOBAL__(): void
                {
                    if (!isset($_GET['info'])) {
                        $_GET['info'] = \Vendor\Models\XSettings::get__(['thumbs_jpg_to_webp', 'thumbs_jpg_to_jpg', 'thumbs_png_to_web', 'thumbs_png_to_jpg', 'thumbs_webp_to_webp']);
                    }

                    // __THUMBS_FORMATS__
                        if (!isset($_GET['__GLOBAL__']['__THUMBS_FORMATS__'])) {
                            $_GET['__GLOBAL__']['__THUMBS_FORMATS__'] = (object)[
                                'thumbs_jpg_to_webp' => $_GET['info']->thumbs_jpg_to_webp??0,
                                'thumbs_jpg_to_jpg' => $_GET['info']->thumbs_jpg_to_jpg??0,
                                'thumbs_png_to_web' => $_GET['info']->thumbs_png_to_web??0,
                                'thumbs_png_to_jpg' => $_GET['info']->thumbs_png_to_jpg??0,
                                'thumbs_webp_to_webp' => $_GET['info']->thumbs_webp_to_webp??0,
                            ];
                        }
                    // __THUMBS_FORMATS__

                    // __MAX_UPLOAD_IMAGE__
                        if (isset($_SERVER['HTTP_HOST'])) {
                            if ((stripos($_SERVER['REQUEST_URI'], '/admin/') !== false) || (stripos($_SERVER['REQUEST_URI'], '/menu_admin/') !== false)) {
                                $_GET['__GLOBAL__']['__MAX_UPLOAD_IMAGE__'] = ADMIN__MAX_UPLOAD_IMAGE;
                                $_GET['__GLOBAL__']['__MAX_UPLOAD_FILES__'] = ADMIN__MAX_UPLOAD_FILES;
                            } else {
                                $_GET['__GLOBAL__']['__MAX_UPLOAD_IMAGE__'] = MAX_UPLOAD_IMAGE;
                                $_GET['__GLOBAL__']['__MAX_UPLOAD_FILES__'] = MAX_UPLOAD_FILES;
                            }
                        }
                    // __MAX_UPLOAD_IMAGE__
                }
            }
        // __GLOBAL__
    // DEFAULT




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // ARRAY
    // ARRAY




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // DATE
        // DATE__
            if (!function_exists('data')) {
                function date__(string|int|null $value, string $format = DATE_FORMAT): ?string
                {
                    if ($value === null || $value === '') {
                        return null;
                    }

                    if (is_int($value) || ctype_digit((string) $value)) {
                        return Carbon::createFromTimestamp((int) $value)->format($format);
                    }

                    $formats = [
                        'Y-m-d',        // 2025-01-21
                        'd/m/Y',        // 21/01/2025
                        'd-m-Y',        // 21-01-2025
                        'Y-m-d H:i:s',  // 2025-01-21 15:23:59
                        'd/m/Y H:i:s',  // 21/01/2025 15:23:59
                    ];

                    foreach ($formats as $fmt) {
                        try {
                            $dt = Carbon::createFromFormat('!' . $fmt, $value);
                            if ($dt !== false) {
                                return $dt->format($format);
                            }
                        } catch (\Throwable $e) {
                        }
                    }

                    try {
                        return Carbon::parse($value)->format($format);
                    } catch (\Throwable $e) {
                        return null;
                    }
                }
            }
        // DATE__


        // DATE_ADD
            if (!function_exists('date_add')) {
                function date_add__(string|int|null $date, int $number, string $unit = 'd', string $format = 'Y-m-d'): ?string
                {
                    try {
                        $dt = (is_int($date) || ctype_digit((string) $date))
                            ? Carbon::createFromTimestamp($date)
                            : Carbon::parse($date);
                    } catch (\Throwable $e) {
                        return null; // data inválida
                    }

                    $map = [
                        'y' => 'year',
                        'm' => 'month',
                        'w' => 'week',
                        'd' => 'day',
                        'h' => 'hour',
                        'i' => 'minute',
                        's' => 'second',
                    ];

                    $unit = strtolower($unit);
                    if (!isset($map[$unit])) {
                        return null; // unidade desconhecida
                    }

                    $dt->modify("{$number} {$map[$unit]}");
                    return $dt->format($format);
                }
            }
        // DATE_ADD


        // DATE_SUM
            if (!function_exists('date_sum')) {
                function date_sum(string|int|null $date1, string|int|null $date2, string $units = 's'): ?array // 'y w d m s'
                {
                    try {
                        $dt1 = (is_int($date1) || ctype_digit((string) $date1))
                            ? Carbon::createFromTimestamp($date1)
                            : Carbon::parse($date1);

                        $dt2 = (is_int($date2) || ctype_digit((string) $date2))
                            ? Carbon::createFromTimestamp($date2)
                            : Carbon::parse($date2);
                    } catch (\Throwable $e) {
                        return null; // data inválida
                    }

                    $sign = $dt2->gt($dt1) ? '+' : '-';
                    $diffSeconds = abs($dt1->getTimestamp() - $dt2->getTimestamp());

                    $secondsPer = [
                        'y' => 365 * 86400,
                        'w' => 7   * 86400,
                        'd' => 1   * 86400,
                        'h' => 3600,
                        'm' => 60,
                        's' => 1,
                    ];

                    $requested = preg_split('/\s+/', trim($units));
                    $result    = ['sign' => $sign];

                    foreach ($requested as $u) {
                        if (!isset($secondsPer[$u])) {
                            return null; // unidade desconhecida
                        }
                        $value        = intdiv($diffSeconds, $secondsPer[$u]);
                        $diffSeconds -= $value * $secondsPer[$u];
                        $result[$u]   = $value;
                    }

                    return $result; // sempre array associativo
                }
            }
        // DATE_SUM


        // DATE_BD
            if (!function_exists('date_bd')) {
                function date_bd(): string
                {
                    $row = DB::select('SELECT NOW() as now');
                    return $row[0]->now;
                }
            }
        // DATE_BD


        // DATE_VALIDATE
            if (!function_exists('date_validate')) {
                function date_validate(string|int|null $value, string|array $formats = 'Y-m-d', string|null $tz = null): bool
                {
                    if ($value === null || $value === '') {
                        return false;
                    }

                    if (is_int($value) || ctype_digit((string) $value)) {
                        return true;
                    }

                    foreach ((array) $formats as $fmt) {
                        $dt = DateTimeImmutable::createFromFormat('!' . $fmt, $value);
                        if ($dt && $dt->format($fmt) === $value) {
                            return true;
                        }
                    }

                    return false;
                }
            }
        // DATE_VALIDATE


        // AGE
            if (!function_exists('age')) {
                function age(string $data, string $tipo = '-'): int
                {
                    $return = 0;
                    $data	= explode($tipo, $data);
                    if (isset($data[2])) {
                        $dia	= (int)$data[2];
                        $mes	= (int)$data[1];
                        $ano	= (int)$data[0];
                        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
                        $nascimento = mktime(0, 0, 0, (int)$mes, (int)$dia, (int)$ano);
                        $return = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
                    }
                    return $return;
                }
            }
        // AGE


        // WEEK
            if (!function_exists('week')) {
                function week(string $data, string $tipo = '-'): string
                {
                    $data	= explode($tipo, $data);
                    $dia	= (int)$data[2];
                    $mes	= (int)$data[1];
                    $ano	= (int)$data[0];
                    $diasemana = date("w", mktime(0, 0, 0, (int)$mes, (int)$dia, (int)$ano));
                
                    switch($diasemana) {
                        case 0: $return = "Domingo";	break;
                        case 1: $return = "Segunda";	break;
                        case 2: $return = "Terça";		break;
                        case 3: $return = "Quarta";	break;
                        case 4: $return = "Quinta";	break;
                        case 5: $return = "Sexta";		break;
                        case 6: $return = "Sábado";	break;
                    }
                    return $return;
                }
            }
        // WEEK


        // MONTH
            if (!function_exists('month')) {
                function month(int|string $mes, int $ab = 0): string
                {
                    $return = '';
                    switch($mes) {
                        case 1: ($return = $ab ? 'Jan'  : 'Janeiro');		break;
                        case 2: ($return = $ab ? 'Fev'  : 'Fevereiro');	break;
                        case 3: ($return = $ab ? 'Mar'  : 'Março');		break;
                        case 4: ($return = $ab ? 'Abr'  : 'Abril');		break;
                        case 5: ($return = $ab ? 'Mai'  : 'Maio');		break;
                        case 6: ($return = $ab ? 'Jun'  : 'Junho');		break;
                        case 7: ($return = $ab ? 'Jul'  : 'Julho');		break;
                        case 8: ($return = $ab ? 'Ago'  : 'Agosto');		break;
                        case 9: ($return = $ab ? 'Set'  : 'Setembro');	break;
                        case 10: ($return = $ab ? 'Out' : 'Outubro');		break;
                        case 11: ($return = $ab ? 'Nov' : 'Novembro');	break;
                        case 12: ($return = $ab ? 'Dez' : 'Dezembro');	break;
                    }
                    return $return;
                }
            }
        // MONTH
    // DATE




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // NUMBERS
        // PRICE
            if (!function_exists('price')) {
                function price(float|int|string|null $price, int $ballot = 0, int $decimal = 2, string $signal = ',', string $signal1 = '.', int $no_zero = 0): string
                {
                    if (CURRENCY == '$' && $signal == ',' && $signal1 == '.') {
                        $signal = '.';
                        $signal1 = ',';
                    }


                    $price = floatval($price);
                    $decimal = ((int)$decimal || $decimal=='0') ? (int)$decimal : 2;
                    $price = number_format($price, $decimal, $signal, $signal1);

                    if ($no_zero) {
                        $price = replace($signal1, '', $price);
                        $price = replace($signal, '.', $price);
                        $price = $no_zero ? (double)$price : $price;
                        $price = replace('.', $signal, $price);
                    }

                    $return =  $price;
                    if ($ballot == 1) {
                        $return = CURRENCY.' '.$price;
                    } else if ($ballot == 2) {
                        $return = CURRENCY.'&nbsp;'.$price;
                    }
                    return $return;
                }
            }
            if (!function_exists('price_number')) {
                function price_number(float|int|string|null $price, int $ballot = 0, int $decimal = 2, string $signal = '.', string $signal1 = '', int $no_zero = 0): string
                {
                    return price($price, $ballot, $decimal, $signal, $signal1, $no_zero);
                }
            }
        // PRICE


        // PRICE__
            if (!function_exists('price_')) {
                function price_(string | null $price): string
                {
                    $price = preg_replace( // eliminar R$ R$ ...
                        '/(?:R\$\s*) {2,}/', 
                        '', 
                        $price
                    );
                    $price = replace(CURRENCY.' ', '', $price);
                    $price = replace(CURRENCY, '', $price);

                    if (compare__(',', $price)) {
                        $return = preg_replace(array('/[^0-9,]/i', '/[-]+/') , '', $price);
                        $return = replace(',', '.', $return);
                        return $return;
                    }
                    return $price;
                }
            }
        // PRICE__


        // DECIMAL
            function decimal(float|int|string|null $number, int $decimal = 2): string
            {
                return rtrim(rtrim(number_format($number, $decimal, '.', ''), '0'), '.');
            }
        // DECIMAL


        // ZERO_LEFT
            function zero_left(int|string|null $numero, int $tamanho = 2): string
            {
                return str_pad((string)$numero, $tamanho, '0', STR_PAD_LEFT);
            }
        // ZERO_LEFT


        // NUMBER
            if (!function_exists('number')) {
                function number(string|null $str): string
                {
                    return preg_replace("/[^0-9]/", "", $str);
                }
            }
            if (!function_exists('number1')) {
                function number1(string|null $str): float|int
                {
                    $return = replace(',', '', $str);
                    $return = $return * 1;
                    return $return;
                }
            }
        // NUMBER


        // PHONE
            if (!function_exists('phone')) {
                function phone(string|null $numero): string{
                    $return = $numero;
                    if (mb_strlen($numero)>14) {
                        $numero = replace('-', '', $numero);
                        $ini = mb_substr($numero, 0, 6);
                        $center = mb_substr($numero, 6, 4);
                        $fim = mb_substr($numero, -4);
                        $return = $ini.' '.$center.'-'.$fim;
                    } else if (mb_strlen($numero)==13) {
                        $numero = replace('-', '', $numero);
                        $ini = mb_substr($numero, 0, 5);
                        $center = mb_substr($numero, 5, 4);
                        $fim = mb_substr($numero, -4);
                        $return = $ini.' '.$center.'-'.$fim;
                    }
                    return $return;
                }
            }
            if (!function_exists('phone_ddd')) {
                function phone_ddd(string|null $numero): string{
                    $return = entre('(', ')', $numero);
                    return $return;
                }
            }
            if (!function_exists('phone_number')) {
                function phone_number(string|null $numero): string{
                    $return = explode(')', $numero);
                    $return[1] = isset($return[1]) ? $return[1] : $return[0];
                    $return = trim($return[1]);
                    $return = replace(' ', '', $return);
                    $return = replace('-', '', $return);
                    return $return;
                }
            }
            if (!function_exists('phone_complete')) {
                function phone_complete(string|null $numero): string{
                    $return = phone_ddd($numero).phone_number($numero);
                    $return = replace(' ', '', $return);
                    return $return;
                }
            }
            if (!function_exists('phone_format')) {
                function phone_format(string|null $numero): string{
                    $numero = '+'.$numero;
                    $numero = replace('+55', '', $numero);
                    $numero = replace('+5', '', $numero);
                    $numero = replace('+', '', $numero);
                    $numero = replace('(', '', $numero);
                    $numero = replace(')', '', $numero);
                    $numero = replace('-', '', $numero);
                    $numero = replace(' ', '', $numero);

                    if (strlen($numero) === 11) {
                        return sprintf("(%s) %s-%s",
                            substr($numero, 0, 2), // DDD
                            substr($numero, 2, 5), // Primeira parte do número
                            substr($numero, 7)    // Segunda parte do número
                        );

                    } else {
                        return sprintf("(%s) %s-%s",
                            substr($numero, 0, 2), // DDD
                            substr($numero, 2, 4), // Primeira parte do número
                            substr($numero, 6)    // Segunda parte do número
                        );
                    }
                }
            }
        // PHONE
    // NUMBERS




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // TREATMENT
        // REVERSE
            if (!function_exists('reverse')) {
                function reverse(mixed $query): array
                {
                    $query = json_encode($query);
                    $query = json_decode($query);    
                    $return = array_reverse($query);
                    return $return;
                }
            }
        // REVERSE


        // JOIN__
            if (!function_exists('join__')) {
                function join__(mixed $collection_1, mixed $collection_2): array
                {
                    $array_1 = collection__to__array($collection_1);
                    $array_2 = collection__to__array($collection_2);

                    $return = array_merge($array_2, $array_1);
                    return $return;
                }
            }
        // JOIN__


        // ARR
            if (!function_exists('collection__to__array')) {
                function collection__to__array(mixed $collection): array
                {
                    return $collection ? (is_array($collection) ? $collection : $collection->toArray())  : [];
                }
            }
        // ARR


        // OBJECT / ARRAY
            if (!function_exists('obj__arr')) {
                function obj__arr(object|array $array, string $field='id'): array
                {
                    $key_no_exist = 0;
                    
                    $return = [];
                    foreach ($array as $key => $value) {
                        if (isset($value->$field)) {
                            $return[$value->$field] = $value;
                        } else {
                            $key_no_exist = 1;
                        }
                    }

                    if ($key_no_exist) {
                        return (array)$array;
                    }
                    return $return;
                }
            }
            if (!function_exists('object__array')) {
                function object__array(object|array $array, string $field='id'): array
                {
                    return obj__arr($array, $field);
                }
            }
            if (!function_exists('arr_obj')) {
                function arr_obj(array|object $array, string $field='id'): object
                {
                    return (object)$array;
                }
            }            
            if (!function_exists('array_object')) {
                function array_object(array|object $array, string $field='id'): object
                {
                    return arr_obj($array, $field);
                }
            }
        // OBJECT / ARRAY


        // FEATURE
            if (!function_exists('feature')) {
                function feature(string $value): array{
                    $return = [0];
                    $ex  = explode('-', $value);
                    foreach ($ex as $key => $value) {
                        if ($value) {
                            $return[] = $value;
                        }
                    }
                    return $return;
                }
            }
            if (!function_exists('feature__')) {
                function feature__(string $value): string{
                    $return  = $value;
                    $return .= $value ? '' : '-';
                    return $return;
                }
            }
        // FEATURE


        // MODULES__TREATMENT
            if (!function_exists('MODULES__TREATMENT')) {
                function MODULES__TREATMENT(array $arr): array
                {
                    $_GET['__MODULES_DASHBOARD__'] = 1;

                    // COLUMNS
                        foreach ($arr['OBJ']['COLUMNS'] as $key => $value) {
                            $arr['OBJ']['COLUMNS'][$key]->check = 'true';
                            $arr['OBJ']['COLUMNS'][$key]->type = $arr['OBJ']['COLUMNS'][$key]->type ?? 'text';
                            $arr['OBJ']['COLUMNS'][$key]->table_align = $arr['OBJ']['COLUMNS'][$key]->align ?? 'tac';    
                        }
                        $arr['OBJ']['menu_admin']->columns = $arr['OBJ']['COLUMNS'];
                    // COLUMNS


                    // INPUT
                        $temp = $arr['OBJ']['menu_admin']->input;
                        $arr['OBJ']['menu_admin']->input = [];
                        foreach ($temp as $key => $value) {
                            foreach ($value as $key_1 => $value_1) {

                                $item = (object)[
                                    "check" => 'true',
                                    "align" => $key ? 'right' : 'left',

                                    "title" => $value_1->title ?? '',
                                    "label" => $value_1->label ?? '',
                                    "name" => $value_1->name ?? '',

                                    "wr" => isset($value_1->wr) ? ( compare__('wr', $value_1->wr) ? $value_1->wr : (is_number($value_1->wr) ? 'wr'.$value_1->wr : 'wr12') ) : 'wr12',
                                    "type" => $value_1->type ?? 'text',
                                    "tags" => $value_1->tags ?? '',

                                    "options" => '',
                                    "options_filter" => $value_1->options_filter ?? '',
                                    "extra" => $value_1->extra ?? '',

                                    "width" => $value_1->width ?? '',
                                    "no_sel" => $value_1->no_sel ?? '',
                                ];

                                // TAGS
                                    if (isset($value_1->required)) {
                                        $item->tags .= ' required';
                                    }
                                // TAGS

                                // OPTIONS / EXTRA
                                    if (isset($value_1->options)) {
                                        if (is_string($value_1->options) && compare__('|->>', $value_1->options)) {
                                            $item->extra = $value_1->options;
                                        } else {
                                            if (is_array($value_1->options) || is_object($value_1->options)) {
                                                $item->extra = '|->>' . implode('; ', array_map(fn($key, $value) => "$key: $value", array_keys($value_1->options), $value_1->options));

                                            } else {
                                                $item->options = $value_1->options ?? '';
                                            }
                                        }    
                                    }
                                // OPTIONS / EXTRA

                                $arr['OBJ']['menu_admin']->input[] = $item;
                            }
                        }
                    // INPUT

                    return $arr;
                }
            }
        // MODULES__TREATMENT
    // TREATMENT




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // IS / IN
        // IS_NUMBER
            if (!function_exists('is_number')) {
                function is_number(mixed $string): bool
                {
                    return is_numeric($string);
                }
            }
        // IS_NUMBER


        // IS_JSON
            if (!function_exists('is_json')) {
                function is_json(mixed $string): bool
                {
                    if (!is_string($string)) {
                        return false;
                    }

                    json_decode($string);
                    return (json_last_error() === JSON_ERROR_NONE);
                }
            }
        // IS_JSON


        // IS_COLLECTION
            if (!function_exists('is_collection')) {
                function is_collection(mixed $collection): bool
                {
                    return $collection instanceof \Illuminate\Support\Collection;
                }
            }
        // IS_COLLECTION
        
    
        // IS_BASE64
            if (!function_exists('is_base64')) {
                function is_base64(mixed $string): bool
                {
                    if (!is_string($string)) {
                        return false;
                    }

                    // Verifica se a string tem o comprimento válido
                    if (strlen($string) % 4 != 0) {
                        return false;
                    }
                
                    // Verifica se a string contém apenas caracteres válidos de base64
                    if (!preg_match('/^[A-Za-z0-9+\/=]*$/', $string)) {
                        return false;
                    }
                
                    // Decodifica a string e reencoda para verificar se é uma base64 válida
                    $decoded = base64_decode($string, true);
                    if ($decoded === false) {
                        return false;
                    }
                
                    // Verifica se a string original é igual à string reencodada
                    if (base64_encode($decoded) !== $string) {
                        return false;
                    }
                
                    return true;
                }
            }
        // IS_BASE64


        // IS_DATE
            if (!function_exists('is_date')) {
                function is_date(string | null $date, string $format = 'Y-m-d'): bool
                {
                    if(!$date){
                        return '';
                    }

                    $d = DateTime::createFromFormat($format, $date);
                    return $d && $d->format($format) === $date;
                }
            }
        // IS_DATE


        // IS_PJ
            if (!function_exists('is_pj')) {
                function is_pj(string $data): bool
                {
                    $data = preg_replace('/\D/', '', $data);
                    if (strlen($data) >= 14) {
                        return true;
                    }
                    return false;
                }
            }
        // IS_PJ


        // IS_IMAGE
            if (!function_exists('is_image')) {
                function is_image(string $path): bool
                {
                    if (!is_file($path) || !is_readable($path)) {
                        return false;
                    }

                    $type = @exif_imagetype($path);
                    if ($type === false) {
                        return false;
                    }

                    $validTypes = [
                        IMAGETYPE_GIF,
                        IMAGETYPE_JPEG,
                        IMAGETYPE_PNG,
                        IMAGETYPE_BMP,
                        IMAGETYPE_WEBP,
                        IMAGETYPE_TIFF_II,
                        IMAGETYPE_TIFF_MM,
                        IMAGETYPE_ICO,
                    ];

                    return in_array($type, $validTypes, true);
                }
            }
            if (!function_exists('is_image_files')) {
                function is_image_files(mixed $image): bool
                {
                    return (isset($image) && is_array($image));
                }
            }
        // IS_IMAGE


        // IS_CARD_NUMBER
            if (!function_exists('is_card_number')) {
                function is_card_number(string $card_number): bool
                {
                    $card_number__ = preg_replace('/[^0-9]/', '', $card_number);

                    if (strlen($card_number__) < 13 || strlen($card_number__) > 19) {
                        return false;
                    }
              
                    if ($card_number__ === '0000000000000000') {
                        return false;
                    }
              
                    // Algoritmo de Luhn
                    $soma = 0;
                    $digits = str_split(strrev($card_number__));
              
                    foreach ($digits as $i => $char) {
                        $n = intval($char);
              
                        if ($i % 2 === 1) {
                            $n *= 2;
                            if ($n > 9) {
                                $n -= 9;
                            }
                        }
                        $soma += $n;
                    }
              
                    if ($soma % 10 !== 0) {
                        return false;
                    }
              
                    return true;
                }
            }
        // IS_CARD_NUMBER


        // IS_CARD_EXPIRATION_DATE
            if (!function_exists('is_card_expiration_date')) {
                function is_card_expiration_date(array $date): bool
                {
                    $card_expiration_month = (int)($date[0]??'');
                    $card_expiration_year = (int)($date[1]??'');

                    $currentDate = new DateTime();
                    $currentYear = (int)$currentDate->format('y');
                    $currentMonth = (int)$currentDate->format('n');

                    if (
                        $card_expiration_year < $currentYear
                        || ($card_expiration_year === $currentYear && $card_expiration_month < $currentMonth)
                    ) {
                        return false;
                    }

                    if (!is_numeric($card_expiration_year) || strlen((string)$card_expiration_year) !== 2) {
                        return false;
                    }
              
                    if (!is_numeric($card_expiration_month) || $card_expiration_month < 1 || $card_expiration_month > 12) {
                        return false;
                    }              

                    return true;
                }
            }
        // IS_CARD_EXPIRATION_DATE
    // IS / IN




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // USEFUL
        // PAGES_SIGN
            function pages_sign(array|Request $request): bool
            {
                $pages = ['login', 'sign_up', 'forget_password', 'error404'];
                if (in_array($request['GET'][0]??[], $pages)) {
                    return true;
                }
                return false;
            }
        // PAGES_SIGN


        // FILTER__
            function filter__(mixed $arary, string $id, string $column = 'name', string $column_id = 'id'): string
            {
                $return = '';
                foreach($arary as $key => $value) {
                    if ($value->{$column_id} == $id) {
                        if (isset($value->{$column})) {
                            $return = $value->{$column};
                            break;
                        }
                    }
                }
                return $return;
            }
        // FILTER__


        // VALUE__
            function value__(Request $request, object $menu_admin, string $key__): string|array
            {
                $request__ = clone $request;
                if (!isset($_GET['__EDIT__'])) {

                    // IF COOKIES EXIST
                        if (COOKIES('SEARCH__'.$key__) && !isset($request['init__'])) {
                            return COOKIES('SEARCH__'.$key__);
                        }
                    // IF COOKIES EXIST

                    // ELSE
                        else {
                            foreach($menu_admin->input as $key => $value) {
                                if (isset($value->name) && isset($value->check) && ($value->check === true || $value->check === 'true')) {
                                    $name = replace('search_dinamic_', '', $key__);
                                    if ($value->name == $name) {
                                        if (isset($value->extra) && compare__('|->value__->', $value->extra)) {
                                            $extra = extra($value->extra, '|->value__');

                                            if (isset($extra[2]) && compare__('|', $extra[2])) {
                                                $extra[1] = explode('|', $extra[2]);
                                            }
                                            
                                            return $extra[1];
                                        }
                                    }
                                }
                            }
                        }
                    // ELSE
                }

                return '';
            }
        // VALUE__


        // QUERY_NAME
            function query_rel(mixed $array, ?string $item, string $column = 'id'): string
            {
                $return = '';
                foreach($array as $key_1 => $value_1) {
                    if ($item == $value_1->{$column}) {
                        $return = $value_1->name;
                    }
                }
                return $return;
            }
        // QUERY_NAME


        // EX
            function ex(string $match, string $value): array
            {
                if (empty($value)) {
                    return [];
                }

                $parts = explode($match, $value);
                $trimmed = array_map('trim', $parts);
                return array_filter($trimmed, fn($item) => $item !== '');
            }
        // EX


        // EXTRA -> ex.: extra($value->extra, '|->options_columns');
            function extra(string $extra, string $type): array
            {
                $ex = explode($type, $extra);
                if (isset($ex[1]) && $ex[1]) {
                    $ex_1 = explode(' |->', $ex[1]);

                    if (isset($ex_1[0]) && $ex_1[0]) {
                        $ex_2 = explode(' -->', $ex_1[0]);

                        if (isset($ex_2[0]) && $ex_2[0]) {
                            return explode('->', $ex_2[0]);
                        }
                    }
                }
                return [];
            }
        // EXTRA ->


        // PAY_METHOD
            function pay_method(string $pay_method): string
            {
                if ($pay_method == 'pix') {
                    return 'Pix';
                }
                if ($pay_method == 'boleto') {
                    return 'Boleto';
                }
                if ($pay_method == 'card_credito') {
                    return 'Cartão de Crédito';
                }

                return '';
            }
        // PAY_METHOD


        // PERC_3
            function perc_3(float $number, float $number_2, int $response_in_perc = 1, int $negative = 0): float
            {
                if ($response_in_perc) {
                    if ($number_2 == 0) return 0;
                    $response = ($number * 100) / $number_2;

                } else {
                    $response = ($number * $number_2) / 100;
                }

                if ($negative) {
                    return $response;
                } else {
                    return min($response, 100);
                }
            }
        // PERC_3


        // EDITOR
            if (!function_exists('editor_base64')) {
                function editor_base64(string $base64): string
                {
                    $text = replace('/web/photos/', DIR.'/web/photos/', base64_decode($base64) );
                    return '<div class="__EDITOR__">'.$text.'</div>';
                }
            }
        // EDITOR


        // SQL_JSON_TRUE
            if (!function_exists('sql_json_true')) {
                function sql_json_true(mixed $input): array
                {
                    // STRING
                        if (is_string($input)) {
                            $decoded = json_decode($input, true);
                            $array = is_array($decoded) ? $decoded : [];
                        }
                    // STRING


                    // OBJECT
                        else if (is_object($input)) {
                            $array = (array) $input;
                        }
                    // OBJECT


                    // ARRAY
                        else if (is_array($input)) {
                            $array = $input;
                        }
                    // ARRAY


                    // ELSE
                        else {
                            $array = [];
                        }
                    // ELSE

                    $filtered = array_filter($array, function ($value) {
                        return $value === true || $value === 'true';
                    });

                    return !empty($filtered) ? $filtered : [];
                }
            }
        // SQL_JSON_TRUE


        // SQL_JSON_LIST
            if (!function_exists('sql_json_list')) {
                function sql_json_list(array|object $array): array
                {
                    if (is_object($array)) {
                        $array = (array) $array;
                    }

                    return array_keys(
                        array_filter($array, fn ($v) => $v === 'true')
                    );
                }
            }
        // SQL_JSON_LIST


        // SQL_JSON_TO_ARRAY
            if (!function_exists('sql_json_to_array')) {
                function sql_json_to_array(array|object $array): array
                {
                    $arr = [];
                    foreach($array as $key => $value) {
                        if($value == 'true' || $value == true) {
                            $arr[] = $key;
                        }
                    }
                    return $arr;
                }
            }
        // SQL_JSON_TO_ARRAY


        // IMAGE
            if (!function_exists('image')) {
                function image(Request $request, Request|array $request__, array $array, array $type = []): array
                {
                    foreach ($array as $key => $value) {
                        if ($request->hasFile($value)) {
                            $request__[$value] = [
                                'request' => $request,
                                'column' => !is_number($key) ? $key : $value,
                                'type' => $type[$key] ?? 'image'
                            ];
                        }
                    }
                    return $request__;
                }
            }
        // IMAGE


        // VERIFY COLOR
            if (!function_exists('verify_color')) {
                function verify_color(string $cor): int{
                    $return = 0;
                    $red = hexdec(substr($cor, 1, 2));
                    $green = hexdec(substr($cor, 3, 2));
                    $blue = hexdec(substr($cor, 5, 2));
                    $resultado = (($red * 299) + ($green * 587) + ($blue * 114)) / 1000;
                    if ($resultado < 128) {
                        $return = 1;
                    }
                    return $return;
                }
            }
        // VERIFY COLOR


        // NL2BR
            if (!function_exists('textarea')) {
                function textarea(string $text): string
                {
                    return $text ? nl2br($text) : '';
                }
            }
        // NL2BR


        // SEACH_FILE
            if (!function_exists('seach_file')) {
                function seach_file(string $seach_file, string $directory, int $like = 0): string
                {
                    if (is_dir($directory)) {
                        $files = scandir($directory);

                        foreach ($files as $file) {
                            if ($file !== '.' && $file !== '..') {

                                // NAME
                                    if (!$like && $file == $seach_file) {
                                        return file_get_contents($directory.'/'.$file);
                                    }
                                // NAME

                                // SEARCH NAME FILE
                                    if ($like == 1 && strpos($file, $seach_file) !== false) {
                                        return file_get_contents($directory.'/'.$file);
                                    }
                                // SEARCH NAME FILE

                                // SEARCH NAME CONTENT
                                    if ($like == 2) {
                                        $filePath = $directory . '/' . $file;
                                        if (is_file($filePath)) {
                                            $content = file_get_contents($filePath);
                            
                                            if ($like && strpos($content, $seach_file) !== false) {
                                                return $content;
                                            }
                                        }
                                    }
                                // SEARCH NAME CONTENT
                            }
                        }
                    }        
                    return '';
                }
            }
        // SEACH_FILE

        // PRODUCTS_CATEGORIES
            if (!function_exists('products_categories')) {
                function products_categories(array $array): array
                {
                    $return = [];

                    // PAGINATE
                        if (isset($array['data']) && is_array($array['data'])) {
                            $paginate = $array;
                            $array = $array['data'];
                        }
                    // PAGINATE

                    // TREATMENT
                        $categories = [];
                        foreach ($array as $key => $value) {
                            if (isset($value->categories)) {
                                $categories[$value->categories] = $value->categories;
                            }
                        }

                        if (count($categories) > 0) {
                            // IDS
                                $cate_1__ = ProductsCategories::whereIn('id', $categories)->get();
                                $sub_1__ = $cate_1__->pluck('subcategories')->filter()->unique()->toArray();

                                $cate_2__ = ProductsCategories::whereIn('id', $sub_1__)->get();
                                $sub_2__ = $cate_2__->pluck('subcategories')->filter()->unique()->toArray();

                                $ids = array_merge($categories, $sub_1__, $sub_2__);
                            // IDS

                            $ProductsCategories = ProductsCategories::whereIn('id', $ids)->get()->keyBy('id');

                            foreach ($array as $key => $value) {
                                if (isset($value->categories)) {
                                    if (isset($ProductsCategories[$value->categories]->name)) {

                                        // CATE
                                            $cate = $ProductsCategories[$value->categories]->name;
                                            if ($ProductsCategories[$value->categories]->subcategories) {

                                                // SUB 1
                                                    $sub_1_id = $ProductsCategories[$value->categories]->subcategories;
                                                    if (isset($ProductsCategories[$sub_1_id]->name)) {
                                                        $sub_1 = '<div class="pb4">('.$ProductsCategories[$sub_1_id]->name.')</div>';

                                                        // SUB 2
                                                            $sub_2_id = $ProductsCategories[$sub_1_id]->subcategories;
                                                            if (isset($ProductsCategories[$sub_2_id]->name)) {
                                                                $sub_2 = '<div class="pb2">('.$ProductsCategories[$sub_2_id]->name.')</div>';
                                                            }
                                                        // SUB 2
                                                    }
                                                // SUB 1
                                            }
                                        // CATE

                                        if (isset($sub_1) && $sub_1) {
                                            $value->categories__ = (isset($sub_2) ? $sub_2 : '').$sub_1.'('.$cate.')';
                                        } else {
                                            $value->categories__ = $cate;
                                        }
                                    }
                                }
                            }
                        }
                    // TREATMENT

                    // PAGINATE
                        if (isset($paginate)) {
                            $paginate['data'] = $return;
                            $return = $paginate;
                        }
                    // PAGINATE

                    return $array;
                }
            }
        // PRODUCTS_CATEGORIES


        // SELECT_GROUP
            if (!function_exists('select_group')) {
                function select_group(mixed $array, int $sub = 0): array
                {
                    if (is_collection($array)) {
                        $array = $array->toArray();
                    }

                    $return = [];

                    // CATEGORIAS
                        foreach ($array as $key => $value) {
                            if ($value['type'] == 0) {

                                // SUBCATEGORIAS (ARRAY)
                                    if ($sub) {
                                        $value['sub'] = [];
                                        foreach ($array as $key_1 => $value_1) {
                                            if ($value_1['type'] == 1 && $value['id'] == $value_1['subcategories']) {
                                                $value['sub'][] = $value_1;
                                            }
                                        }
                                    }
                                // SUBCATEGORIAS (ARRAY)

                                $return[] = $value;

                                // SUBCATEGORIAS (--)
                                    if (!$sub) {
                                        foreach ($array as $key_1 => $value_1) {
                                            if ($value_1['type'] == 1 && $value['id'] == $value_1['subcategories']) {
                                                $value_1['name'] = '-- '.$value_1['name'];
                                                $return[] = $value_1;
                                            }
                                        }
                                    }
                                // SUBCATEGORIAS (--)
                            }                        
                        }
                    // CATEGORIAS

                    return $return;
                }
            }
        // SELECT_GROUP


        // SELECT_GROUP
            if (!function_exists('select_group_array')) {
                function select_group_array(mixed $array): array
                {
                    if (is_collection($array)) {
                        $array = $array->toArray();
                    }

                    $return = [];

                    // CATEGORIAS
                        foreach ($array as $key => $value) {
                            if ($value['type'] == 0) {
                                $value['sub'] = [];


                                $return[] = $value;
                            }                        
                        }
                    // CATEGORIAS

                    return $return;
                }
            }
        // SELECT_GROUP


        // REMOVE_NULL
            if (!function_exists('remove_null')) {
                function remove_null(array $array): array
                {
                    $json = json_encode($array);
                    $array = json_decode($json, true);

                    foreach ($array as $key => $value) {
                        if (is_null($value) || $value === '') {
                            unset($array[$key]);

                        } else if (is_array($value)) {
                            $array[$key] = remove_null($value);
                            if (empty($array[$key])) {
                                unset($array[$key]);
                            }
                        }
                    }
                    return $array;
                }
            }
        // REMOVE_NULL


        // HELLO
            if (!function_exists('hello')) {
                function hello(): string
                {
                    $horaAtual = date('H');
                    if ($horaAtual >= 0 && $horaAtual < 12) {
                        return "Bom dia";
                    } else if ($horaAtual >= 12 && $horaAtual < 18) {
                        return "Boa tarde";
                    } else {
                        return "Boa noite";
                    }
                }
            }
        // HELLO


        // ADDRESS
            if (!function_exists('address_1')) {
                function address_1(object $value): string
                {
                    $return = '';
                    $return .= $value->address ?? '';
                    $return .= isset($value->number) ? ', ' . $value->number . ' ' : '';
                    $return .= $value->complement ?? '';
                    return $return;
                }
            }
            if (!function_exists('address_2')) {
                function address_2(object $value): string
                {
                    $return = '';
                    $return .= isset($value->neighborhood) ? $value->neighborhood : '';
                    return $return;
                }
            }
            if (!function_exists('address_3')) {
                function address_3(object $value): string
                {
                    $return = '';
                    $return .= $value->city.'/'.$value->uf;
                    return $return;
                }
            }
            if (!function_exists('address_4')) {
                function address_4(object $value): string
                {
                    return $value->zipcode ?? '';
                }
            }
        // ADDRESS


        // TABLE__MODEL
            if (!function_exists('table__model')) {
                function table__model(string $table): string
                {
                    $return = replace('_', ' ', $table);
                    $return = ucwords($return);
                    $return = replace(' ', '', $return);
                    return $return;
                }
            }
        // TABLE__MODEL


        // COMPARE__
            if (!function_exists('compare__')) {
                function compare__(string $match, mixed $txt): bool
                {
                    if ($txt instanceof Expression) {
                        return false;
                    }

                    if (stripos($txt, $match) !== false) {
                        return true;
                    }
                    return false;
                }
            }

            if (!function_exists('compare__ini')) {
                function compare__ini(string $match, string $txt): bool
                {
                    return str_contains($txt, $match);
                }
            }

            if (!function_exists('compare__fim')) {
                function compare__fim(string $match, string $txt): bool
                {
                    return str_ends_with($txt, $match);
                }
            }
        // COMPARE__


        // JSON__
            if (!function_exists('json__')) {
                function json__(string $file, array|object|null $array = null): mixed
                {
                    $return = (object)[];

                    // DIR
                        $dir = DIR_D.'/json'.dirname($file).'/';
                        if (!file_exists($dir)) { mkdir($dir, 0755, true); }
                    // DIR

                    // FILE
                        $file = DIR_D.'/json'.$file;
                        if (file_exists($file)) {
                            $return = json_decode(file_get_contents($file));
                        }
                    // FILE

                    // MERGE
                        if ($array) {
                            if (is_array($array)) {
                                $array['json'] = $return;
                                $return = $array;
                            }
                            if (is_object($array)) {
                                $array->json = $return;
                                $return = $array;
                            }
                        }
                    // MERGE
                
                    return $return;
                }
                function data_json__(string $file): mixed
                {
                    $return = (object)[];

                
                    return $return;
                }
            }
        // JSON__


        // LOCATION
            if (!function_exists('location')) {
                function location(string $url): \Illuminate\Http\RedirectResponse{
                    return redirect()->to($url);
                }
            }
        // LOCATION


        // TOKEN / CODE
            if (!function_exists('token')) {
                function token(int $tamanho = 10, bool $letra = true, bool $numeros = true, bool $simbolos = false): string
                {
                    $char = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    //$char = 'abcdefghijklmnopqrstuvwxyz';
                    $num = '12345678901234567890';
                    $simb = '!@#$%*-';
                    $return = '';
                    $caracteres = '';
                    if ($letra) $caracteres .= $char;
                    if ($numeros) $caracteres .= $num;
                    if ($simbolos) $caracteres .= $simb;
                    $len = mb_strlen($caracteres);
                    for ($n = 1; $n <= $tamanho; $n++) {
                        $rand = mt_rand(1, $len);
                        $return .= $caracteres[$rand-1];
                    }
                    return $return;
                }
            }
            if (!function_exists('code')) {
                function code(int $tamanho = 10, bool $letra = true, bool $numeros = true, bool $simbolos = false): string
                {
                    return token($tamanho, $letra, $numeros, $simbolos);
                }
            }
        // TOKEN / CODE


        // JSON_ENCODE__ / SESSION_APP //JSON//
            if (!function_exists('json_encode__')) {
                function json_encode__(array $arr, Request|null $request = null): JsonResponse
                {
                    // DEFAULT
                        if ($request) {
                            if (LUGAR_ADMIN()) {
                                $arr = ADMIN__DEFAULT__DATA($arr, $request);
                            } else {
                                $arr = DEFAULT__DATA($arr, $request);
                            }
                        }
                    // DEFAULT


                    //EXTRAS
                        $arr = all__json_encode__extra__($arr, $request);
                    //EXTRAS

                    return response()->json($arr);
                }
            }
        // JSON_ENCODE__ / SESSION_APP


        // json_decode__
            if (!function_exists('json_decode__')) {
                function json_decode__(string $json): mixed
                {
                    $return = replace("'", '"', $json);
                    $return = replace("\\n", " ", $return);
                    $return = replace("\n", " ", $return);
                    return json_decode($return);
                }
            }            
        // json_decode__


        // UTF8
            if (!function_exists('utf8_encode__')) {
                function utf8_encode__(string $text): string
                {
                    //$text = mb_convert_encoding($text, 'UTF-8', 'ISO-8859-1');
                    return $text;
                }
            }
            if (!function_exists('utf8_decode__')) {
                function utf8_decode__(string $text): string
                {
                    //$text = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
                    return $text;
                }
            }
        // UTF8


        // REPLACE
            if (!function_exists('replace')) {
                function replace(mixed $trocarIsso, mixed $porIsso, mixed $txt): mixed
                {
                    if ($txt && !is_array($txt) && !is_object($txt)) {
                        $return = str_replace($trocarIsso, $porIsso, $txt);
                    } else {
                        $return = $txt;
                    }
                    return $return;
                }
            }
        // REPLACE


        // MENU_JSON
            if (!function_exists('menu_json')) {
                function menu_json(): string{
                    return DIR_F.'/_root/z_Json/menu_admin';
                }
            }
        // MENU_JSON

        // IN
            if (!function_exists('in')) {
                function in(mixed $collection, string $field = 'id'): array
                {
                    $return = [];
                    foreach ($collection as $key => $value) {
                        $value = (array)$value;
                        $return[] = $value[$field];
                    }
                    return $return;
                }
            }
        // IN


        // IDS
            if (!function_exists('ids')) {
                function ids(mixed $array): array
                {
                    $return = [];

                    // ARRAY
                        if (is_array($array)) {
                            $return = $array;
                        }
                    // ARRAY

                    // OBJECT
                        else if (is_object($array)) {
                            $return = (array)$array;
                        }
                    // OBJECT

                    else {
                        // ENTRE VIRGULA ex: 1,2,3
                            $ex = explode(',', $array);
                            if (isset($ex[1])) {
                                $return = $ex;

                            }
                        // ENTRE VIRGULA ex: 1,2,3

                        // STRING
                            else {
                                $return[] = $array;
                            }
                        // STRING
                    }

                    return $return;
                }
            }
            if (!function_exists('sel__ids')) {
                function sel__ids(Request $request, object $_MODEL): array
                {
                    $return = [];

                    // ID
                        if (isset($request['id'])) {
                            $return[] = $request['id'];
                        }
                    // ID

                    // SEL_ALL_ALL
                        else if ($request['sel_all_all']) {
                            $items = $_MODEL->get();
                            foreach ($items as $key => $value) {
                                $return[] = $value->id;
                            }
                        }
                    // SEL_ALL_ALL

                    // SEL
                        else {
                            foreach ($request['sel'] as $key => $value) {
                                if ($value) {
                                    $return[] = $key;
                                }
                            }
                        }
                    // SEL

                    return $return;
                }
            }
        // IDS


        // PAGINATE
            if (!function_exists('paginate')) {
                function paginate(array $paginate): array
                {
                    $paginate['first_page_url'] = replace(DIR.'/api', '', $paginate['first_page_url']);
                    $paginate['last_page_url'] = replace(DIR.'/api', '', $paginate['last_page_url']);
                    $paginate['next_page_url'] = replace(DIR.'/api', '', $paginate['next_page_url']);
                    $paginate['path'] = replace(DIR.'/api', '', $paginate['path']);

                    foreach ($paginate['links'] as $key => $value) {
                        if ($key == 0) {
                            $paginate['links'][$key]['url'] = replace(DIR.'/api', '', $paginate['links'][1]['url']);
                            $paginate['links'][$key]['label'] = '<';

                        } else if ($key+1 == count($paginate['links'])) {
                            $paginate['links'][$key]['url'] = replace(DIR.'/api', '', $paginate['links'][$key-1]['url']);
                            $paginate['links'][$key]['label'] = '>';

                        } else {
                            $paginate['links'][$key]['url'] = replace(DIR.'/api', '', $paginate['links'][$key]['url']);
                        }
                    }

                    return $paginate;
                }
            }
        // PAGINATE


        // LIMIT
            if (!function_exists('limit')) {
                function limit(string $text, int $limit, string $ellipsis = '...', int $tirm = 1): string
                {
                    if ( mb_strlen($text) > $limit ) {
                        $text = mb_substr($text, 0, $limit);
                        if ($tirm) {
                            $text = trim($text);
                        }
                        $text .= $ellipsis;
                    }
                    return $text;
                }
            }
            if (!function_exists('limit1')) {
                function limit1(string $text, int $limit, int $numero = 0): string
                {
                    if ($numero) {
                        $text = str_pad($text, $limit, 0, STR_PAD_LEFT);
                    } else {
                        //$text = $text.'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ';
                        $text = $text;
                        $text = limit($text, $limit,'', 0);
                    }
                    return $text;
                }
            }
        // LIMIT


        // NAME
            if (!function_exists('name') && !function_exists('fullname')) {
                function name(string $name, int $tipo = 0): string // 0-> So nome; 1->So Sobrenome; 2->x qty de nomes
                {
                    $ex = explode(' ', $name);

                    // NOME
                        if ($tipo == 0) {
                            return $ex[0];
                        }
                    // NOME

                    // SOBRENOME
                        if ($tipo == 1) {
                            $return = isset($ex[1]) ? $ex[1] : '';

                            // SO NOME FOR MENOR QUE X VAR
                                if (isset($ex[1]) && mb_strlen($ex[1]) < 4) {
                                    $return .= (isset($ex[2]) && $ex[2]) ? ' '.$ex[2] : '';
                                }
                                if (isset($ex[2]) && mb_strlen($ex[2]) < 4) {
                                    $return .= (isset($ex[3]) && $ex[3]) ? ' '.$ex[3] : '';
                                }
                            // SO NOME FOR MENOR QUE X VAR

                            return $return;
                        }
                    // SOBRENOME

                    // QTY
                        if ($tipo >= 2) {
                            $return = '';
                            for ($i = 0; $i < $tipo; $i++) {
                                if (isset($ex[$i])) {
                                    $return .= $ex[$i] ? $ex[$i].' ' : '';

                                    // SO NOME FOR MENOR QUE X VAR
                                        if (isset($ex[$i]) && mb_strlen($ex[$i]) < 4 && $i == ($tipo-1)) {
                                            $i++;
                                            $return .= (isset($ex[$i]) && $ex[$i]) ? $ex[$i].' ' : '';
                                        }
                                    // SO NOME FOR MENOR QUE X VAR
                                }
                            }
                            $return = trim($return);

                            return $return;
                        }
                    // QTY

                    return $name;
                }
            }
            if (!function_exists('fullname')) {
                function fullname(string $name, int $tipo = 0): string
                {
                    return name($name, $tipo);
                }
            }

            if (!function_exists('name_ini')) {
                function name_ini(string $name): string 
                {
                    $return = '';
                
                    if (!empty($name)) {
                        $ex = explode(' ', $name);
                
                        $return = strtoupper($ex[0][0]);
                        if (isset($ex[1][0])) {
                            $return .= strtoupper($ex[1][0]);
                        } else {
                            $return .= strtolower($ex[0][1] ?? '');
                            $return .= strtolower($ex[0][2] ?? '');
                        }
                    }
                
                    $return = str_replace(['-', '_'], '', $return);
                
                    return $return;
                }
            }
        // NAME


        // ERRORS
            if (!function_exists('errors__')) {
                function errors__(mixed $array_errors, array $arr = []): array
                {
                    $return = [];

                    // ERROR DO AR
                        if (isset($arr['errors'])) {
                            $return['errors'] = $arr['errors'];
                        }
                    // ERROR DO AR

                    // ERROR EXCEPTION/THROWABLE
                        else {
                            if ($array_errors instanceof \Throwable) {
                                $error_message = $array_errors->getMessage();
                                
                                // SE A MENSAGEM ESTIVER VAZIA, PEGAR MAIS DETALHES
                                    if (empty($error_message)) {
                                        $error_message = get_class($array_errors) . ' na linha ' . $array_errors->getLine() . ' do arquivo ' . basename($array_errors->getFile());
                                    }
                                // SE A MENSAGEM ESTIVER VAZIA, PEGAR MAIS DETALHES
                                
                                // ADICIONAR INFORMAÇÕES EXTRAS SE FOR AMBIENTE LOCAL
                                    if (LOCALHOST) {
                                        $error_message .= ' | Arquivo: ' . $array_errors->getFile() . ' | Linha: ' . $array_errors->getLine();
                                    }
                                // ADICIONAR INFORMAÇÕES EXTRAS SE FOR AMBIENTE LOCAL
                                
                                $return['errors'] = $error_message;

                            } else {
                                // USAR A FUNÇÃO ORIGINAL SE NÃO FOR UM THROWABLE
                                    $error_text = strip_tags(errors__1($array_errors));
                                // USAR A FUNÇÃO ORIGINAL SE NÃO FOR UM THROWABLE
                                
                                // SE MESMO ASSIM ESTIVER VAZIO, TENTAR CONVERTER PARA STRING
                                    if (empty($error_text)) {
                                        if (is_array($array_errors) || is_object($array_errors)) {
                                            $error_text = 'Erro: ' . json_encode($array_errors, JSON_UNESCAPED_UNICODE);
                                        } else {
                                            $error_text = (string)$array_errors;
                                        }
                                    }
                                // SE MESMO ASSIM ESTIVER VAZIO, TENTAR CONVERTER PARA STRING
                                
                                $return['errors'] = $error_text;
                            }
                        }
                    // ERROR EXCEPTION/THROWABLE

                    if (!$return['errors']) {
                        $return['errors'] = '{{ try }}';
                    }

                    return $return;
                }
            }
            if (!function_exists('errors__1')) {
                function errors__1(mixed $array_errors): string
                {
                    $return = [];

                    if (is_array($array_errors) || is_object($array_errors)) {
                        foreach ($array_errors as $key => $value) {
                            if ($key <> "xdebug_message" || LOCALHOST) {
                                $return[] = errors__1($value);
                            }
                        }
                    } else {
                        $return[] = $array_errors;
                    }
                    $return = implode(' | ', $return);

                    return $return;
                }
            }
        // ERRORS


        // LOWER
            if (!function_exists('lower')) {
                function lower(string $txt): string
                {
                    $trocarIsso = array('Â', 'â', 'Ê', 'ê', 'Î', 'î', 'Ô', 'ô', 'Û', 'û', 'Ã', 'ã', 'Õ', 'õ', 'Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú', 'À', 'à', 'È', 'è', 'Ì', 'ì', 'Ò', 'ò', 'Ù', 'ù', 'Ç', 'ç');
                    $porIsso    = array('(am->flex)', '(a->flex)', '(em->flex)', '(e->flex)', '(im->flex)', '(i->flex)', '(om->flex)', '(o->flex)', '(um->flex)', '(u->flex)', '(am->tio)', '(a->tio)', '(om->tio)', '(o->tio)', '(am->agudo)', '(a->agudo)', '(em->agudo)', '(e->agudo)', '(im->agudo)', '(i->agudo)', '(om->agudo)', '(o->agudo)', '(um->agudo)', '(u->agudo)', '(am->crase)', '(a->crase)', '(em->crase)', '(e->crase)', '(im->crase)', '(i->crase)', '(om->crase)', '(o->crase)', '(um->crase)', '(u->crase)', '(cm->cedilha)', '(c->cedilha)');
                    $txt = strtolower(replace($trocarIsso, $porIsso, $txt));
                    $trocarIsso = array('(am->flex)', '(a->flex)', '(em->flex)', '(e->flex)', '(im->flex)', '(i->flex)', '(om->flex)', '(o->flex)', '(um->flex)', '(u->flex)', '(am->tio)', '(a->tio)', '(om->tio)', '(o->tio)', '(am->agudo)', '(a->agudo)', '(em->agudo)', '(e->agudo)', '(im->agudo)', '(i->agudo)', '(om->agudo)', '(o->agudo)', '(um->agudo)', '(u->agudo)', '(am->crase)', '(a->crase)', '(em->crase)', '(e->crase)', '(im->crase)', '(i->crase)', '(om->crase)', '(o->crase)', '(um->crase)', '(u->crase)', '(cm->cedilha)', '(c->cedilha)');
                    $porIsso    = array('â', 'â', 'ê', 'ê', 'î', 'î', 'ô', 'ô', 'û', 'û', 'ã', 'ã', 'õ', 'õ', 'á', 'á', 'é', 'é', 'í', 'í', 'ó', 'ó', 'ú', 'ú', 'à', 'à', 'è', 'è', 'ì', 'ì', 'ò', 'ò', 'ù', 'ù', 'ç', 'ç');
                    $return = replace($trocarIsso, $porIsso, $txt);
                    return $return;
                }
            }
        // LOWER


        // UPPER
            if (!function_exists('upper')) {
                function upper(string $txt): string
                {
                    $trocarIsso = array('Â', 'â', 'Ê', 'ê', 'Î', 'î', 'Ô', 'ô', 'Û', 'û', 'Ã', 'ã', 'Õ', 'õ', 'Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú', 'À', 'à', 'È', 'è', 'Ì', 'ì', 'Ò', 'ò', 'Ù', 'ù', 'Ç', 'ç');
                    $porIsso    = array('(am->flex)', '(a->flex)', '(em->flex)', '(e->flex)', '(im->flex)', '(i->flex)', '(om->flex)', '(o->flex)', '(um->flex)', '(u->flex)', '(am->tio)', '(a->tio)', '(om->tio)', '(o->tio)', '(am->agudo)', '(a->agudo)', '(em->agudo)', '(e->agudo)', '(im->agudo)', '(i->agudo)', '(om->agudo)', '(o->agudo)', '(um->agudo)', '(u->agudo)', '(am->crase)', '(a->crase)', '(em->crase)', '(e->crase)', '(im->crase)', '(i->crase)', '(om->crase)', '(o->crase)', '(um->crase)', '(u->crase)', '(cm->cedilha)', '(c->cedilha)');
                    $txt = strtoupper(replace($trocarIsso, $porIsso, $txt));
                    $trocarIsso = array('(am->flex)', '(a->flex)', '(em->flex)', '(e->flex)', '(im->flex)', '(i->flex)', '(om->flex)', '(o->flex)', '(um->flex)', '(u->flex)', '(am->tio)', '(a->tio)', '(om->tio)', '(o->tio)', '(am->agudo)', '(a->agudo)', '(em->agudo)', '(e->agudo)', '(im->agudo)', '(i->agudo)', '(om->agudo)', '(o->agudo)', '(um->agudo)', '(u->agudo)', '(am->crase)', '(a->crase)', '(em->crase)', '(e->crase)', '(im->crase)', '(i->crase)', '(om->crase)', '(o->crase)', '(um->crase)', '(u->crase)', '(cm->cedilha)', '(c->cedilha)');
                    $porIsso    = array('Â', 'â', 'Ê', 'ê', 'Î', 'î', 'Ô', 'ô', 'Û', 'û', 'Ã', 'ã', 'Õ', 'õ', 'Á', 'á', 'É', 'é', 'Í', 'í', 'Ó', 'ó', 'Ú', 'ú', 'À', 'à', 'È', 'è', 'Ì', 'ì', 'Ò', 'ò', 'Ù', 'ù', 'Ç', 'ç');
                    $return = replace($trocarIsso, $porIsso, $txt);
                    return $return;
                }
            }
        // UPPER


        // ENTRE
            if (!function_exists('entre')) {
                function entre(string $ini, string $fim, string $item): string{
                    $ex = explode($ini, $item);
                    $ex = isset($ex[1]) ? explode($fim, $ex[1]) : '';
                    $return = isset($ex[0]) ? $ex[0] : '';
                    return $return;
                }
            }
            if (!function_exists('entre_array')) {
                function entre_array(string $ini, string $fim, string $item): array{
                    $return = entre_array1($ini, $fim, $item, array());
                    return $return;
                }
            }
            if (!function_exists('entre_array1')) {
                function entre_array1(string $ini, string $fim, string $item, array $return): array{
                    $entre = entre($ini, $fim, $item);
                    if ($entre) {
                        $return[] = $entre;
                        $return = entre_array1($ini, $fim, replace('['.$entre.']', '', $item), $return);
                    }
                    return $return;
                }
            }
        // ENTRE


        // MOBILE
            if (!function_exists('MOBILE')) {
                function MOBILE(): bool{
                    $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
                    if (strpos($userAgent, 'mobi') !== false) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        // MOBILE


        // URL_UNICA
            if (!function_exists('url_unica')) {
                function url_unica(string $url): string|array{
                    $error = 0;

                    $url = lower($url);
                    $url = not('symbols', $url);


                    // MIN CHAR
                        if (strlen($url) < 5 && !$error) {
                            $error = 'O nome de usuário deve ter pelo menos 5 caracteres!';
                        }
                    // MIN CHAR


                    // BANCO
                        $Customers = Customers::where('type', 'customers')->where('url', $url)->first();
                        if (isset($Customers->id) && !$error) {
                            $error = 'Este nome de usuário inserido já está cadastrado, insira outro nome de usuário!';
                        }
                    // BANCO


                    // ROUTER
                        $routes = Route::getRoutes();
                        foreach ($routes as $key => $value) {
                            if (compare__($url, $value->uri) && !$error) {
                                $error = 'Este nome de usuário inserido já está cadastrado, insira outro nome de usuário!';
                            }
                        }
                    // ROUTER


                    if ($error) {
                        return ['error' => $error];
                    }
                    return $url;
                }
            }
        // URL_UNICA

        // CRIP
            if (!function_exists('crip_1')) {
                function crip_1(string $value): string{
                    $array_1 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
                    $array_2 = array('A', 'C', 'BI', 'M', 'S', 'LO', 'FU', 'N', 'R', 'DE');
                    for ($i=0; $i < 10; $i++) { 
                        $value = replace($array_1[$i], $array_2[$i], $value);
                    }
                    return $value;
                }
            }
            if (!function_exists('crip_2')) {
            function crip_2(string $value): string{
                    $array_1 = array('A', 'C', 'BI', 'M', 'S', 'LO', 'FU', 'N', 'R', 'DE');
                    $array_2 = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
                    for ($i=0; $i < 10; $i++) { 
                        $value = replace($array_1[$i], $array_2[$i], $value);
                    }
                    return $value;
                }
            }
        // CRIP


        // MODEL__ROOT__OR__ALL
            if (!function_exists('MODEL__ROOT__OR__ALL')) {
                function MODEL__ROOT__OR__ALL(string $model__table): string
                {
                    $table = table__model($model__table);
                    $table = class_basename($table);

                    if (file_exists(__DIR__.'/../../_root/Models/'.$table.'.php')) {
                        return 'Root\\Models\\'.$table;

                    } else {
                        return 'Vendor\\Models\\'.$table;
                    }
                }
            }
        // MODEL__ROOT__OR__ALL


        // ALL__MAX_UPLOAD_IMAGE_LIMIT_WIDHT
            if (!function_exists('ALL__MAX_UPLOAD_IMAGE_LIMIT_WIDHT')) {
                function ALL__MAX_UPLOAD_IMAGE_LIMIT_WIDHT(): int
                {
                    if (LUGAR_ADMIN()) {
                        return ADMIN__MAX_UPLOAD_IMAGE_LIMIT_WIDHT;
                    } else {
                        return MAX_UPLOAD_IMAGE_LIMIT_WIDHT;
                    }
                }
            }
        // ALL__MAX_UPLOAD_IMAGE_LIMIT_WIDHT


        // ALL__MAX_UPLOAD_IMAGE__MAX
            if (!function_exists('ALL__MAX_UPLOAD_IMAGE__MAX')) {
                function ALL__MAX_UPLOAD_IMAGE__MAX(): int
                {
                    if (LUGAR_ADMIN()) {
                        return ADMIN__MAX_UPLOAD_IMAGE__MAX;
                    } else {
                        return MAX_UPLOAD_IMAGE__MAX;
                    }
                }
            }
        // ALL__MAX_UPLOAD_IMAGE__MAX


        // ALL__MAX_UPLOAD_IMAGE
            if (!function_exists('ALL__MAX_UPLOAD_IMAGE')) {
                function ALL__MAX_UPLOAD_IMAGE(): int
                {
                    if (LUGAR_ADMIN()) {
                        return ADMIN__MAX_UPLOAD_IMAGE;
                    } else {
                        return MAX_UPLOAD_IMAGE;
                    }
                }
            }
        // ALL__MAX_UPLOAD_IMAGE


        // ALL__MAX_UPLOAD_FILES
            if (!function_exists('ALL__MAX_UPLOAD_FILES')) {
                function ALL__MAX_UPLOAD_FILES(): int
                {
                    if (LUGAR_ADMIN()) {
                        return ADMIN__MAX_UPLOAD_FILES;
                    } else {
                        return MAX_UPLOAD_FILES;
                    }
                }
            }
        // ALL__MAX_UPLOAD_FILES


        // COOKIES
            if (!function_exists('COOKIES')) {
                function COOKIES(string $name): string
                {
                    return !empty($_COOKIE[$name]) ? $_COOKIE[$name] : '';
                }
            }
            if (!function_exists('isset_COOKIES')) {
                function isset_COOKIES(string $name): bool
                {
                    if(isset(request()['init__'])){
                        return false;
                    }
                    return (COOKIES($name) && COOKIES($name) != '' && COOKIES($name) != 'null');
                }
            }
            if (!function_exists('COOKIES_CREATE')) {
                function COOKIES_CREATE(string $name, string $cookie, int $time = 60 * 60, string $path = "/"): void
                {
                    setcookie($name, $cookie, time() + $time, $path);
                }
            }
            if (!function_exists('COOKIES_LIST')) {
                function COOKIES_LIST(): array
                {
                    $list = [];
                
                    foreach ($_COOKIE as $name => $value) {
                        $list[] = [
                            'name'  => urldecode($name),
                            'value' => urldecode($value),
                        ];
                    }
                
                    return $list;
                }
            }
            if (!function_exists('COOKIES_DELETE')) {
                function COOKIES_DELETE(string $name): void
                {
                    setcookie($name, '', time() - 3600, '/');
                }
            }
        // COOKIES


        // PERMISSIONS
            if (!function_exists('PERMISSIONS')) {
                function PERMISSIONS(Request $request): bool
                {
                    $return = false;
                    if (isset($request->user()->id)) {
                        if (!in_array('permissions_all', $_GET['fillable__']['customers']??[])) {
                            $return = true;

                        } else {
                            // PERMISSIONS_ALL
                                if ($request->user()->currentAccessToken()->tokenable->permissions_all == 1) {
                                    $return = true;
                                }
                            // PERMISSIONS_ALL


                            // PERMISSIONS
                                if ($request->user()->currentAccessToken()->tokenable->permissions) {
                                    $permissions = sql_json_list($request->user()->currentAccessToken()->tokenable->permissions);
                                    if (
                                        in_array('/'.$request['GET'][1], $permissions)
                                        || in_array('/'.$request['GET'][1].'/'.$request['GET'][2], $permissions)
                                        || in_array('/'.$request['GET'][1].'/'.$request['GET'][2].'/'.$request['GET'][3], $permissions)
                                        || in_array('/'.$request['GET'][1].'/'.$request['GET'][2].'/'.$request['GET'][3].'/'.$request['GET'][4], $permissions)
                                        || in_array('/'.$request['GET'][1].'/'.$request['GET'][2].'/'.$request['GET'][3].'/'.$request['GET'][4].'/'.$request['GET'][5], $permissions)
                                    ) {
                                        $return = true;
                                    }
                                    if ($request['GET'][1] == '') {
                                        $return = true;
                                    }
                                }
                            // PERMISSIONS
                        }

                    } else {
                        $return = true;
                    }

                    return $return;
                }
            }
        // PERMISSIONS


        // NAME__FUNCTION_TO_TABLE
            function name__function_to_table($classe)
            {
                // Primeiro, adiciona underscore antes de letras maiúsculas (exceto a primeira)
                $result = preg_replace('/(?<!^)([A-Z])/', '_$1', class_basename($classe));

                // Depois, adiciona underscore antes de números
                $result = preg_replace('/(\d+)/', '_$1', $result);

                // Remove underscores duplicados e converte para minúsculas
                $result = preg_replace('/_+/', '_', $result);

                return strtolower($result);
            }
        // NAME__FUNCTION_TO_TABLE


        // CARD_BRAND
            function card_brand($number): string
            {
                $number = preg_replace('/\D/', '', $number);                
                $firstDigits = substr($number, 0, 6);
                
                if (substr($number, 0, 1) == '4') {
                    return 'visa';
                }
                elseif (preg_match('/^5[1-5]/', $firstDigits) || preg_match('/^2[2-7]/', $firstDigits)) {
                    return 'mastercard';
                }
                elseif (preg_match('/^3[47]/', substr($number, 0, 2))) {
                    return 'amex';
                }
                elseif (preg_match('/^3(?:0[0-5]|[68])/', substr($number, 0, 3))) {
                    return 'diners';
                }
                elseif (preg_match('/^6(?:011|5)/', $firstDigits)) {
                    return 'discover';
                }
                elseif (preg_match('/^(?:2131|1800|35\d{3})/', $firstDigits)) {
                    return 'jcb';
                }
                elseif (preg_match('/^(401178|401179|431274|438935|451416|457393|457631|457632|504175|627780|636297|636368|655000|655001|651652|651653|651654|655000|655001|655002|655003|506699|506700|506701|506702|506703|506704|506705|506706|506707|506708|506709|506710|506711|506712|506713|506714|506715|506716|506717|506718|509000|509001|509002|509003|509004|509005|509006|509007|509008|509009|636368|636369|636370)/', $firstDigits)) {
                    return 'elo';
                }
                elseif (preg_match('/^(38|60)/', substr($number, 0, 2))) {
                    return 'hipercard';
                }
                
                return '';
            }
        // CARD_BRAND
    // USEFUL




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // TESTS / EXPLICATIONS
        // COMPARE DB
            function compare_db(): array
            {
                $return = [];

                if (LOCALHOST) {
                    $db1_config['host'] = env('DB_HOST');
                    $db1_config['database'] = env('DB_DATABASE');
                    $db1_config['username'] = env('DB_USERNAME');
                    $db1_config['password'] = env('DB_PASSWORD');

                    $db2_config['host'] = env('DB_HOST');
                    $db2_config['database'] = 'zz';
                    $db2_config['username'] = env('DB_USERNAME');
                    $db2_config['password'] = env('DB_PASSWORD');

                    try {
                        // Configuração dinâmica da conexão 1
                        config(['database.connections.temp_db1' => [
                            'driver' => $db1_config['driver'] ?? 'mysql',
                            'host' => $db1_config['host'],
                            'port' => $db1_config['port'] ?? 3306,
                            'database' => $db1_config['database'],
                            'username' => $db1_config['username'],
                            'password' => $db1_config['password'],
                            'charset' => $db1_config['charset'] ?? 'utf8mb4',
                            'collation' => $db1_config['collation'] ?? 'utf8mb4_unicode_ci',
                            'prefix' => $db1_config['prefix'] ?? '',
                            'strict' => $db1_config['strict'] ?? true,
                            'engine' => $db1_config['engine'] ?? null,
                        ]]);

                        // Configuração dinâmica da conexão 2
                        config(['database.connections.temp_db2' => [
                            'driver' => $db2_config['driver'] ?? 'mysql',
                            'host' => $db2_config['host'],
                            'port' => $db2_config['port'] ?? 3306,
                            'database' => $db2_config['database'],
                            'username' => $db2_config['username'],
                            'password' => $db2_config['password'],
                            'charset' => $db2_config['charset'] ?? 'utf8mb4',
                            'collation' => $db2_config['collation'] ?? 'utf8mb4_unicode_ci',
                            'prefix' => $db2_config['prefix'] ?? '',
                            'strict' => $db2_config['strict'] ?? true,
                            'engine' => $db2_config['engine'] ?? null,
                        ]]);

                        // Testa conexão com banco 1
                        try {
                            DB::connection('temp_db1')->select('SELECT 1');
                        } catch (\Exception $e) {
                            $return['error'] = "Não foi possível conectar com o Banco 1 (Local): " . $e->getMessage();
                            return $return;
                        }

                        // Testa conexão com banco 2
                        try {
                            DB::connection('temp_db2')->select('SELECT 1');
                        } catch (\Exception $e) {
                            $return['error'] = "Não foi possível conectar com o Banco 2 (Remoto): " . $e->getMessage();
                            return $return;
                        }

                        // 1. Buscar todas as tabelas do banco 1
                        $db1_tables = DB::connection('temp_db1')
                            ->select('SHOW TABLES');
                        
                        $db1_table_names = [];
                        foreach($db1_tables as $table) {
                            $table_array = (array) $table;
                            $db1_table_names[] = array_values($table_array)[0];
                        }

                        // 2. Buscar todas as tabelas do banco 2
                        $db2_tables = DB::connection('temp_db2')
                            ->select('SHOW TABLES');
                        
                        $db2_table_names = [];
                        foreach($db2_tables as $table) {
                            $table_array = (array) $table;
                            $db2_table_names[] = array_values($table_array)[0];
                        }

                        // 3. Encontrar tabelas que estão no banco 1 mas não no banco 2
                        $missing_tables = array_diff($db1_table_names, $db2_table_names);
                        
                        $return['missing_tables'] = $missing_tables;
                        $return['missing_columns'] = [];

                        // 4. Para tabelas que existem em ambos, comparar as colunas
                        $common_tables = array_intersect($db1_table_names, $db2_table_names);
                        
                        foreach($common_tables as $table_name) {
                            // Buscar colunas do banco 1
                            $db1_columns = DB::connection('temp_db1')
                                ->select("SHOW COLUMNS FROM `{$table_name}`");
                            
                            $db1_column_names = [];
                            foreach($db1_columns as $column) {
                                $db1_column_names[] = $column->Field;
                            }

                            // Buscar colunas do banco 2
                            $db2_columns = DB::connection('temp_db2')
                                ->select("SHOW COLUMNS FROM `{$table_name}`");
                            
                            $db2_column_names = [];
                            foreach($db2_columns as $column) {
                                $db2_column_names[] = $column->Field;
                            }

                            // Encontrar colunas que estão no banco 1 mas não no banco 2
                            $missing_columns = array_diff($db1_column_names, $db2_column_names);
                            
                            if (!empty($missing_columns)) {
                                $return['missing_columns'][$table_name] = $missing_columns;
                            }
                        }

                        $return['success'] = true;
                        $return['message'] = 'Comparação realizada com sucesso!';

                        // Limpa as conexões temporárias
                        DB::purge('temp_db1');
                        DB::purge('temp_db2');

                    } catch (\Exception $e) {
                        // Em caso de erro geral
                        $return['error'] = "Erro ao comparar bancos de dados: " . $e->getMessage();
                        
                        // Limpa as conexões e retorna array com erro
                        try {
                            DB::purge('temp_db1');
                            DB::purge('temp_db2');
                        } catch (\Exception $cleanup_error) {
                            // Ignora erros de limpeza
                        }
                    }
                }

                return $return;
            }
        // COMPARE DB



    // TESTS / EXPLICATIONS




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // EDITOR
        // LEGEND
            if (!function_exists('editor_legend')) {
                function editor_legend(array $array): string
                {
                    $return  = '';
                    $return .= '<div class="p10"> ';
                        $return .= '<div class="fz14 fwb6 c_vermelho">Legenda</div> ';

                        $return .= '<div class="fz12 c_vermelho"> ';
                            foreach ($array as $key => $value) {
                                $return .= '<div class="pt10 fz13 fwb5 c_vermelho">'.$value->name.'</div> ';
                                $return .= '<div class="pt2"> ';
                                    foreach ($value->array as $key_1 => $value_1) {
                                        $return .= '<div class="pt2 pb2 pr10 flexx flex_ac "> ';
                                            $return .= '<div class="pr5">'.$value_1.':</div> ';
                                            $return .= '<a dir="{'.$key_1.'}" class="____CKEDITOR_inserir__">{'.$key_1.'}</a> ';
                                            // button_2 c_fff b_blue
                                        $return .= '</div> ';
                                    }
                                $return .= '</div> ';
                            }
                        $return .= '</div> ';
                    $return .= '</div> ';
                    return $return;
                }
            }
        // LEGEND
    // EDITOR




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // PADROES SISTEM
        // PRE
            if (!function_exists('pre')) {
                function pre(mixed $array, int $pre3__exit = 0): void
                {
                    if ($pre3__exit == 1) {
                        $_GET['pre'][] = $array;

                    } else {
                        echo '<pre>';
                            print_r($array);
                        echo '</pre>';
                    }

                    if ($pre3__exit == 2) exit();
                }
            }
            if (!function_exists('pre__')) {
                function pre__(mixed $array): void
                {
                    $_GET['pre'][] = $array;
                }
            }
            if (!function_exists('pre_ob')) {
                function pre_ob(mixed $array): string
                {
                    $return = '';
                    ob_start();
                        echo '<pre>';
                            print_r($array);
                        echo '</pre>';
                        $return .= ob_get_contents();
                    ob_end_clean();

                    return $return;
                }
            }
            if (!function_exists('pre_')) {
                function pre_(array $array, int $exit = 0): void
                {
                    $array_final = [];
                    foreach ($array as $key => $value) {
                        if ($value instanceof Collection) {
                            $array_final[$key] = $value->toArray();
                        } else {
                            $array_final[$key] = $value;    
                        }
                    }

                    echo '<pre>';
                        print_r($array_final);
                    echo '</pre>';

                    if ($exit) exit();
                }
            }
            if (!function_exists('pre_sql')) {
                function pre_sql(object $array, int $exit = 0): void
                {
                    echo '<pre>';
                        print_r($array->toSql());
                    echo '</pre>';

                    if ($exit) exit();
                }
            }
            if (!function_exists('GET_pre_fixed_set')) {
                function GET_pre_fixed_set(mixed $value, string $key = '', int $push = 0): void
                {
                    if ($push) {
                        $_GET['pre_fixed'][$key][] = $value;

                    } else if ($key) {
                        $_GET['pre_fixed'][$key] = $value;

                    } else {
                        $_GET['pre_fixed'][] = $value;
                    }
                }
            }
            if (!function_exists('GET_pre_fixed_read')) {
                function GET_pre_fixed_read(): array
                {
                    return $_GET['pre_fixed'] ?? [];
                }
            }

            if (!function_exists('all__json_encode__extra__')) { //PRE//
                function all__json_encode__extra__(array $arr, Request|null $request): array
                {
                    if (isset($_GET['pre'])) {
                        $arr['pre'] = $_GET['pre'];
                    }
                    if (isset($_GET['pre_fixed'])) {
                        $arr['pre_fixed'] = $_GET['pre_fixed'];
                    }
                    if (isset($_GET['error'])) {
                        $arr['error'] = $_GET['error'];
                    }
                    if (isset($_GET['errors'])) {
                        $arr['errors'] = $_GET['errors'];
                    }
                    if (isset($_GET['errors__try'])) {
                        $arr['errors__try'] = $_GET['errors__try'];
                    }
                    if (isset($_GET['__QUERYS__'])) {
                        foreach ($_GET['__QUERYS__'] as $key => $value) {
                            $arr['OBJ']['__QUERYS__'][$key] = $value;
                        }
                    }
                    if (isset($_GET['__GLOBAL__']) && $request) {
                        $arr['OBJ']['__GLOBAL__'] = $_GET['__GLOBAL__'];
                    }

                    $arr['OBJ']['GET'] = $request['GET']??[];

                    return $arr;
                }
            }
        // PRE


        // BREAK__
            if (!function_exists('break__')) {
                function break__(): string
                {
                    return '
';
                }
            }
        // BREAK__


        // TAB__
            if (!function_exists('tab__')) {
                function tab__(): string
                {
                    return '    ';
                }
            }
        // TAB__


        // SQL
            if (!function_exists('sql')) {
                function sql(object $query, int $sql = 1): void
                {
                    $toSql = __Resource::toSql($query->toSql(), $query->getBindings());
                    if ($sql) {
                        GET_pre_fixed_set($toSql, 'search');
                    }

                    preg_match('/SELECT (.+?) FROM (.+?) WHERE (.+)/i', $toSql, $matches);

                    if (count($matches) === 4) {
                        $where = preg_split('/\s(OR|AND)\s/i', $matches[3], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

                        $conditions = [];
                        for ($i = 0; $i < count($where); $i++) {
                            if (strtoupper($where[$i]) === 'OR' || strtoupper($where[$i]) === 'AND') {
                                $conditions[] = trim(strtoupper($where[$i]));
                            } else {
                                $conditions[] = trim($where[$i]);
                            }
                        }
                    
                        $array = [
                            'select' => trim($matches[1]),
                            'from' => trim($matches[2]),
                            'where' => $conditions
                        ];

                        GET_pre_fixed_set($array, 'search');

                    } else {
                        GET_pre_fixed_set('A query não está no formato esperado.', 'search');
                    }
                }
            }
        // SQL
    // PADROES SISTEM




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // CODIFICATION
        // CRIP
            if (!function_exists('crip')) {
                function crip(string $value): string
                {
                    $substituicoes = array(
                        '0' => 'ENA;;QV',
                        '1' => 'BA-_VEC',
                        '2' => 'SD;;XAY',
                        '3' => 'TO||LUF',
                        '4' => 'LPD_,WU',
                        '5' => 'PFA-;YT',
                        '6' => 'GIV.;ZBX',
                        '7' => 'MED-;HRF',
                        '8' => 'RDB;$JY',
                        '9' => 'TCK#@XFG',
                        '/' => 'NIP;#TIU',
                        ' ' => 'YSU@;IR'
                    );
                    $value = strtr($value, $substituicoes);
                    $value = base64_encode($value);
                    return $value;
                }
            }
            if (!function_exists('crip_')) {
                function crip_(string $value): string
                {
                    $value = base64_decode($value);
                    $substituicoes = array(
                        'ENA;;QV'   => '0',
                        'BA-_VEC'   => '1',
                        'SD;;XAY'   => '2',
                        'TO||LUF'   => '3',
                        'LPD_,WU'   => '4',
                        'PFA-;YT'   => '5',
                        'GIV.;ZBX'  => '6',
                        'MED-;HRF'  => '7',
                        'RDB;$JY'   => '8',
                        'TCK#@XFG'  => '9',
                        'NIP;#TIU'  => '/',
                        'YSU@;IR'   => ' '
                    );                    
                    $value = strtr($value, $substituicoes);
                    return $value;
                }
            }
        // CRIP


        // KEY__
            if (!function_exists('key__')) {
                function key__(array $array): string
                {
                    return array_key_first($array) ?? '';
                }
            }
        // KEY__


        // NOT
            if (!function_exists('not')) {
                function not(string $type, string $text, string $reaplce__ = '-'): string
                {
                    switch ($type) {
                        case 'tags':
                            $return = strip_tags($text, '');
                            break;

                        case 'url':
                            $search	= array(' ',);
                            $replace = array($reaplce__);
                            $text = replace($search, $replace, $text);
                            $search	= array('à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ü','ú','ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ü','Ú','Ÿ',);
                            $replace = array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','Y',);
                            $text = replace($search, $replace, $text);
                            $search	= array('°', 'º', 'ª', 'ª', '#', '%', '&', '?', '\ ', '\\', '\\\ ', "\"", '\'', '/', '"', "'", '´', '`', '~', '^', '!', '@', '#', '$', '%', '¨', '&', '=',  ':', ';', '*', '<', '>', '(', ')', '|', ' ',);
                            $replace = array($reaplce__);
                            $return = replace($search, $replace, $text);
                            break;

                        case 'accents':
                            $search	= array('à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ü','ú','ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ü','Ú','Ÿ',);
                            $replace = array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','Y',);
                            $return = replace($search, $replace, $text);
                            break;

                        case 'accents_all':
                            //cod('asc->html', $text);
                            $text = replace(array('[\', \']'), '', $text);
                            $text = preg_replace('/\[.*\]/U', '', $text);
                            $text = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', $reaplce__, $text);
                            $text = htmlentities($text, ENT_COMPAT, 'utf-8');
                            $text = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $text );
                            $text = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , $reaplce__, $text);
                            $return = strtolower(trim($text, $reaplce__));
                            break;

                        case 'symbols':
                            $search	= array(' ','à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ü','ú','ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ü','Ú','Ÿ', '\ ', '\\', '\\\ ', "\"", '\'', '/',);
                            $replace = array('_','a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','Y', '_',  '_',  '_',    '_',  '_',  '_',);
                            $return = replace($search, $replace, $text);
                            $return = preg_replace("/[^a-zA-Z0-9-\s]/", $reaplce__, $return);
                            break;

                        case 'html->utf8':
                            $return = htmlentities($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            break;

                        case 'utf->html':
                            $return = html_entity_decode($text, 'UTF-8', ENT_QUOTES | ENT_HTML5);
                            break;

                        case 'iso->utf8':
                            $return = mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
                            break;

                        case 'utf8->iso':
                            $return = mb_convert_encoding($text, 'UTF-8', 'ISO-8859-1');
                            break;

                        case 's_end':
                            $rules = [
                                '/ões$/' => 'ão',
                                '/ães$/' => 'ão',
                                '/ões$/' => 'ão',
                                '/ais$/' => 'al',
                                '/éis$/' => 'el',
                                '/óis$/' => 'ol',
                                '/is$/' => 'il',
                                '/res$/' => 'r',
                                '/zes$/' => 'z',
                                '/ses$/' => 's',
                                '/ns$/' => 'm',
                                '/ãos$/' => 'ão',
                                '/ais$/' => 'al',
                                '/eis$/' => 'el',
                                '/ois$/' => 'ol',
                                '/us$/' => 'u',
                                '/s$/' => ''
                            ];
                            foreach ($rules as $pattern => $replacement) {
                                if (preg_match($pattern, $text)) {
                                    return preg_replace($pattern, $replacement, $text);
                                }
                            }
                            $return = $text;
                            break;

                        default:
                            $return = $text;
                    }

                    return $return;
                }
            }
        // NOT


        // TRANSLATE
            if (!function_exists('translate')) {
                function translate(string $text, string $target = 'pt', string $source = 'en'): string
                {
                    // $key = XSettings::get__(['key_google'])->key_google;
                    // if ($key) {
                    //     $url = 'https://translation.googleapis.com/language/translate/v2';
                    //     $data = [
                    //         'q' => $text,
                    //         'target' => $target,
                    //         'source' => $source,
                    //         'key' => XSettings::get__(['key_google'])->key_google
                    //     ];

                    //     $ch = curl_init($url);
                    //     curl_setopt($ch, CURLOPT_POST, 1);
                    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    //         'Content-Type: application/x-www-form-urlencoded'
                    //     ]);
                    
                    //     $result = curl_exec($ch);
                    //     $err = curl_error($ch);
                    //     curl_close($ch);

                    //     if ($result === FALSE) {
                    //         return $text;
                    //     }
                    
                    //     $responseBody = json_decode($result, true);
                    
                    //     if (isset($responseBody['data']['translations'][0]['translatedText'])) {
                    //         return $responseBody['data']['translations'][0]['translatedText'];
                    //     }
                    // }

                    return $text;
                }
            }
            
        // TRANSLATE
    // CODIFICATION




















// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------




















    // ADMIN
        // DEFAULT__DATA (NO STORE && UPDATE)
            if (!function_exists('ADMIN__DEFAULT__DATA')) {
                function ADMIN__DEFAULT__DATA(array $arr, Request $request): array
                {

                    // USER
                        if (isset($request->user()->id)) {
                            $arr['OBJ']['user'] = Users::select(['id', 'name'])->find($request->user()->id);
                        }
                    // USER


                    // MENU_SIDE
                        $query = YMenuAdminCategories_Admin::categories_rel()->orderBy('id', 'asc');
                        $menu_all = $query->get()->toArray();
                        $submenu_all = YMenuAdmin_Admin::whereIn('categories', $query->pluck('id'))->where('active', 1)->where('id', '!=', 1)->whereIn('type', [0, 1])->orderBy('order')->get()->toArray();

                        // VERIFICATION
                            $permissions = [];
                            $permissions_all = '';
                            if (isset($request->user()->id)) {
                                // PERMISSIONS
                                    if ($request->user()->currentAccessToken()->tokenable->permissions) {
                                        $permissions = sql_json_list(json_decode($request->user()->currentAccessToken()->tokenable->permissions, 1));
                                    }
                                // PERMISSIONS

                                // PERMISSIONS_ALL
                                    $permissions_all = $request->user()->currentAccessToken()->tokenable->permissions_all ?? 0;
                                    if ($request->user()->id == 1 || $request->user()->id == 2) {
                                        $permissions_all = 1;
                                    }
                                // PERMISSIONS_ALL
                            }

                            // PERMISSIONS_ALL
                                if ($permissions_all == 1) {
                                    $menu = $menu_all;
                                    $submenu = $submenu_all;
                                }
                            // PERMISSIONS_ALL

                            // PERMISSIONS
                                else {
                                    $submenu = [];
                                    $menu_temp = [];
                                    foreach ($submenu_all as $key => $value) {
                                        if (in_array($value['id'], $permissions)) {
                                            $menu_temp[] = $value['categories'];
                                            $submenu[] = $value;
                                        }
                                    }
                                    $menu = [];
                                    foreach ($menu_all as $key => $value) {
                                        if (in_array($value['id'], $menu_temp)) {
                                            $menu[$key] = $value;
                                        }
                                    }
                                }
                            // PERMISSIONS
                        // VERIFICATION


                        // RELATIONSHIP
                            $array = [];
                            foreach ($menu as $key => $value) {
                                $array_1 = [];
                                foreach ($submenu as $key_1 => $value_1) {
                                    if ($value['id'] == $value_1['categories']) {
                                        $array_1[]  = $value_1;
                                    }
                                }
                                $value['submenu'] = $array_1;
                                $array[$key] = $value;
                            }
                        // RELATIONSHIP


                        // PUTTING MENU ICONS TO SUBMENU THAT DOESN'T HAVE
                            foreach ($array as $key => $value) {
                                foreach ($value['submenu'] as $key_1 => $value_1)
                                {
                                    foreach ($value_1 as $key_2 => $value_2) {
                                        if ($key_2 == 'table__')
                                        {
                                            try {
                                                $class_1 = MODEL__ROOT__OR__ALL($value_1['table__']);
                                                $class__ = new $class_1();
                                                $array[$key]['submenu'][$key_1]['table'] = $class__->table;
                                            } catch (\Throwable $th) { }
                                        }
                                    }
                                }
                            }
                        // PUTTING MENU ICONS TO SUBMENU THAT DOESN'T HAVE

                        
                        // TREATMENT
                            $menu_side = [];
                            foreach ($array as $key => $value) {
                                $menu = [];
                                $submenu = [];
                                foreach ($value['submenu'] as $key_1 => $value_1) {
                                    $icon = [];
                                    if (isset($value_1['icon'])) {
                                        $icon = explode('|', $value_1['icon']);
                                    }

                                    if (!$key_1) {
                                        $menu__ = [
                                            'id' => $value_1['id'],
                                            'name' => $value_1['name'],
                                            'table' => $value_1['table'] ?? '',
                                            'type_items' => $value_1['type_items'] ?? '',
                                            'icon' => $icon[0] ?? '',
                                            'icon_active' => $icon[1] ?? '',
                                            'svg' => $value_1['svg'] ?? null,
                                            'svg_active' => $value_1['svg_active'] ?? null,
                                            'url' => '/modules/'.$value_1['id'],
                                            'pages' => [$value_1['id']],
                                        ];

                                        if (isset($value['title']) && $value['title']) {
                                            if (isset($value_1['table'])) {
                                                $menu = [
                                                    'id' => $value['id'],
                                                    'name' => $value['title'],
                                                    'table' => $value_1['table'],
                                                    'type_items' => $value_1['type_items'] ?? '',
                                                    'icon' => $icon[0] ?? '',
                                                    'icon_active' => $icon[1] ?? '',
                                                    'url' => '/modules/'.$value_1['id'],
                                                ];
        
                                                $submenu[] = $menu__;
                                            }

                                        } else {
                                            $menu = $menu__;
                                        }
            
                                    } else {
                                        $submenu[] = [
                                            'id' => $value_1['id'],
                                            'name' => $value_1['name'],
                                            'table' => $value_1['table'] ?? '',
                                            'type_items' => $value_1['type_items'] ?? '',
                                            'icon' => $icon[0] ?? '',
                                            'icon_active' => $icon[1] ?? '',
                                            'url' => '/modules/'.$value_1['id'],
                                            'pages' => [$value_1['id']],
                                        ];
                                    }
                                }

                                if (isset($submenu) && $submenu) {
                                    $menu['submenu'] = $submenu;
                                }
                                if (isset($menu) && $menu) {
                                    $menu_side[] = $menu;
                                }
                            }
                        // TREATMENT





                        // INICIO
                            $arr['OBJ']['menu_side'] = array_merge([[
                                'id' => 0,
                                'name' => 'Início',
                                'url' => '/',
                                'pages' => ['home'],
                            ]], $menu_side);
                        // INICIO
                    // MENU_SIDE





                    // INFO
                        $arr['OBJ']['info'] = (object)[];
                        $arr['OBJ']['info']->currency = CURRENCY;
                        $arr['OBJ']['info']->currency_decimal = CURRENCY_DECIMAL;
                    // INFO
                    return $arr;
                }
            }
        // DEFAULT__DATA
    // ADMIN




















// ----------------------------------------------------------------------------------------------------------------------------------------------------------




















    // VERSIONS
        // 1.0.0
            // add Services\NEW__UploadService.php

            // add DATE_FORMAT, DATE_FORMAT_MONTH, PHONE_FORMAT in Support\NEW__Functions.php
        // 1.0.0

        // 1.0.1
            // fields_table() -> fields_datatable_() in ..app\_root\Resources\NEW__Resource.php

            // ADD TESTE_MAIL in Support\NEW__Functions.php
        // 1.0.1
    // VERSIONS