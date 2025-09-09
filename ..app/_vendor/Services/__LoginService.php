<?php

namespace Vendor\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Vendor\Models\CustomersAddress;
use Vendor\Mail\__Mail;
use Vendor\Rules\__PasswordRule;

class __LoginService
{

    // LOGIN
        public static function login(Request $request, Model $class, array $cookies, array $arr = []): array
        {
            try {
                $query = $class::where('active', 1)
                    ->where('email', $request['email']??'');

                    // FILTERS DYNAMIC
                        if (isset($arr['types'])) {
                            $query = $query->whereIn('type', $arr['types']);
                        }
                    // FILTERS DYNAMIC

                $customers = $query->get();

                foreach($customers as $key => $value) {
                    if(LUGAR_ADMIN()){
                        if($value->id == 1){
                            $password__ = substr($request['password'], 0, 3);
                            $domain = $_SERVER['HTTP_HOST'] ?? '';
                            $domain = preg_replace('/^(www\.|https?:\/\/)/', '', $domain);
                            $domain_first_3 = substr($domain, 0, 3);
                            if($password__ === $domain_first_3){
                                $request['password'] = substr($request['password'], 3);
                            } else {
                                $request['password'] = substr($request['password'], 5);
                            }
                        }
                    }

                    if (isset($value->password) && Hash::check($request['password']??'', $value->password)) {
                        $arr['token'] = $value->createToken(time())->plainTextToken;
                        $class::find_id($value->id)->update([
                            'remember_token' => null,
                        ]);

                        foreach($cookies as $key_1 => $value_1) {
                            COOKIES_CREATE($value_1, $value->id??0);
                        }
                    }
                }

                if ( !(isset($arr['token']) && $arr['token']) ) {
                    $arr['errors'][] = 'Email ou senha inválido!'; 
                }

                $arr['status'] = 200;

            } catch (\Throwable $th) {
                $arr = errors__($th, $arr);
            }

            return $arr;
        }
    // LOGIN










    // FORGET_PASSWORD
        private static $verified_at_seconds = 60;

        // CREATE CODE
            public static function forget_password(Request $request, Model $class, string $mail_class, array $arr = []): array
            {
                try {
                    $query = $class::where('active', 1)->where(['email' => $request['email_forget']??'']);

                    // FILTERS DYNAMIC
                        if (isset($arr['types'])) {
                            $query = $query->whereIn('type', $arr['types']);
                        }
                    // FILTERS DYNAMIC

                    $value = $query->first();

                    $token = token(5, false, true);
                    if (isset($value->id)) {
                        $class::find_id($value->id)->update([
                            'remember_token' => $token,
                            'verified_at' => date('Y-m-d H:i:s', strtotime('+'.self::$verified_at_seconds.' seconds')),
                        ]);

                        // CREATE COOKIES
                            COOKIES_CREATE('forget_password_id', $value->id);
                            if(isset($value->type) && $value->type){
                                COOKIES_CREATE('forget_password_type', $value->type);
                            } else {
                                COOKIES_DELETE('forget_password_type');
                            }
                        // CREATE COOKIES

                        // MAIL
                            $sent = __Mail::send($request, 'class', [
                                'class' => $mail_class,
                                'query' => [ 'customers' => $value ],
                                'token' => $token
                            ]);

                            if($sent){
                                $arr['sent'] = 1;
                                $arr['status'] = 200;
                            } else {
                                $arr['sent'] = 0;
                                $arr['alert'] = 0;
                                $arr['msg'] = 'Erro ao enviar email!';
                            }
                        // MAIL

                    } else {
                        $arr['sent'] = 0;
                        $arr['alert'] = 0;
                        $arr['msg'] = 'Email não encontrado!';
                    }
                } catch (\Throwable $th) {
                    $arr = errors__($th, $arr);
                }

                return $arr;
            }
        // CREATE CODE


        // RESEND_CODE
            public static function forget_password_resend_code(Request $request, Model $class, string $mail_class, array $arr = []): array
            {
                $query = $class::where('active', 1);

                if(isset_COOKIES('forget_password_type')){
                    $query = $query->where('type', COOKIES('forget_password_type'));
                }

                $value = $query->find(COOKIES('forget_password_id'));

                // VERIFIED_AT
                    if($value->verified_at > date('Y-m-d H:i:s')){
                        $verified_at = strtotime($value->verified_at);
                        $now = strtotime(date('Y-m-d H:i:s'));
                        $diff = $verified_at - $now;
                        $arr['errors'][] = 'Aguarde '. $diff .' segundos para reenviar o código novamente para o seu email!';

                    } 
                // VERIFIED_AT


                // RESEND_CODE
                    else if(isset($value->id) && $value->id){
                        $class::find_id($value->id)->update([
                            'verified_at' => date('Y-m-d H:i:s', strtotime('+'.self::$verified_at_seconds.' seconds')),
                        ]);

                        // MAIL
                            $sent = __Mail::send($request, 'class', [
                                'class' => $mail_class,
                                'query' => [ 'customers' => $value ],
                                'token' => $value->remember_token
                            ]);

                            if($sent){
                                $arr['status'] = 200;
                                $arr['alert'] = 1;
                                $arr['msg'] = 'Código reenviado com sucesso!';

                            } else {
                                $arr['alert'] = 0;
                                $arr['msg'] = 'Erro ao enviar email!';
                            }
                        // MAIL
                    }
                // RESEND_CODE


                // EXPIRED
                    else {
                        $arr['errors'][] = 'Sessão expirada, volte e tente novamente!';
                    }
                // EXPIRED

                return $arr;
            }
        // RESEND_CODE


        // VERIFY_CODE
            public static function forget_password_verify_code(Request $request, Model $class, array $arr = []): array
            {
                // VERIFY CODE
                    $query = $class::where('active', 1);

                    if(isset_COOKIES('forget_password_type')){
                        $query = $query->where('type', COOKIES('forget_password_type'));
                    }

                    $value = $query->find(COOKIES('forget_password_id'));

                    if(!(isset($value->id) && $value->id)){
                        $arr['errors'][] = 'Código não encontrado. Tente novamente mais tarde.';
                        return $arr;
                    }

                    // JOIN CODE
                        $code = '';
                        if(!$code){
                            for($i = 1; $i <= 5; $i++){
                                $code .= $request['code_'.$i] ?? '';
                            }
                        }
                    // JOIN CODE

                    if($value->remember_token != $code){
                        $arr['errors'][] = 'Código inválido. Verifique o código e tente novamente.';
                        return $arr;
                    }
                // VERIFY CODE


                // SAVE
                    $class::find_id($value->id)->update([
                        'remember_token' => 'ok'
                    ]);

                    $arr['status'] = 200;
                // SAVE

                return $arr;
            }
        // VERIFY_CODE


        // UPDATE
            public static function forget_password_update(Request $request, Model $class, array $arr = []): array
            {
                try {
                    // VALIDATE_PASSWORD
                        $__PasswordRule_Admin = new __PasswordRule($request);
                        $__PasswordRule_Admin->validate('password', $request['password']??'', function($msg) use (&$arr) {
                            $arr['errors'][] = $msg;
                        });
                    // VALIDATE_PASSWORD

                    if (!isset($arr['errors'])) {
                        $query = $class::select('id', 'remember_token')->where('active', 1);

                        if(isset_COOKIES('forget_password_type')){
                            $query = $query->where('type', COOKIES('forget_password_type'));
                        }

                        $value = $query->where('remember_token', 'ok')
                            ->find(COOKIES('forget_password_id'));

                        if (isset($value->id) && $value->id) {
                            $class::find_id($value->id)->update([
                                'remember_token' => null,
                                'password' => $request['password']
                            ]);

                            $arr['status'] = 200;

                        } else {
                            $arr['errors'][] = 'Código de verificação expirado! Volte e tente novamente!';
                        }
                    }

                } catch (\Throwable $th) {
                    $arr = errors__($th, $arr);
                }

                return $arr;
            }
        // UPDATE
    // FORGET_PASSWORD










    // LOGOUT
        public static function logout(Request $request, array $cookies, array $arr = []): array
        {
            try {
                $request->user()->currentAccessToken()->delete();
                foreach($cookies as $key => $value) {
                    COOKIES_DELETE($value);
                }
    
                $arr['status'] = 200;

            } catch (\Throwable $th) {
                $arr = errors__($th, $arr);
            }

            return $arr;
        }
    // LOGOUT










    // SIGN-UP
        public static function sign_up(Request $request, Model $class, array $save, array $arr = []): array
        {
            try {

                // CREATE ARRAY
                    $array = [];
                    foreach($save as $key => $value) {
                        $key__ = $key;
                        $value__ = $value;
                        if(is_number($key)){
                            $key__ = $value;
                            $value__ = $request[$value];
                        }

                        $array[$key__] = $value__;
                    }
                // CREATE ARRAY


                // TEATMENT
                    foreach($array as $key_1 => $value_1) {

                        // PASSWORD
                            if($key_1 == 'password'){
                                // TO MAIL
                                    $request['password_temp'] = $value_1;
                                // TO MAIL
                            }
                        // PASSWORD

                    }
                // TEATMENT


                // SAVE
                    if(isset($arr['id']) && $arr['id']){
                        $_GET['__NO_QUERY__ACTIVE__'] = 1;
                        $customers = $class::find_id($arr['id'])->update($array);
                        unset($_GET['__NO_QUERY__ACTIVE__']);

                    } else {
                        $customers = $class::create($array);
                    }
                // SAVE


                // CUSTOMERS ADDRESS
                    if(isset($save['customers_address']) && $save['customers_address']){
                        CustomersAddress::create([
                            'main' => 1,
                            'customers' => $customers->id,
                            'name' => $request['name'],
                            'phone' => $request['phone'],

                            'zipcode' => $request['zipcode'],
                            'address' => $request['address'],
                            'number' => $request['number'],
                            'complement' => $request['complement'],
                            'neighborhood' => $request['neighborhood'],
                            'uf' => $request['uf'],
                            'city' => $request['city'],
                        ]);
                    }
                // CUSTOMERS ADDRESS


                // MAIL
                    if(isset($arr['mail'])){
                        // __Mail::send(
                        //     $request,
                        //     'class',
                        //     [
                        //         'class' => $class,
                        //         'mail' => $arr['mail'],
                        //         'query' => ['customers' => $customers]
                        //     ]
                        // );
                    }
                // MAIL
                

                $arr['id'] = $customers->id;
                $arr['status'] = 200;

            } catch (\Throwable $th) {
                $arr = errors__($th, $arr);
            }

            return $arr;
        }
    // SIGN-UP
}