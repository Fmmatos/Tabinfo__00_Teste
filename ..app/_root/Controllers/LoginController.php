<?php

namespace Root\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Vendor\Mail\__Mail;
use Vendor\Models\Customers;
use Vendor\Requests\Root\__LoginRequest;
use Vendor\Services\__LoginService;

class LoginController
{

    // INDEX
        public function index(Request $request): JsonResponse
        {
            $arr = [];

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // INDEX










    // TYPES
        public function types(Request $request): array
        {
            // TYPE_ONLY
                if (!empty($request['type_only'])) {
                    return [$request['type_only']];
                }
            // TYPE_ONLY


            // GET ARRAYS
                $arrays = [];
                if ($request['GET'][0] == 'dashboard') {
                    $arrays = $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['DASHBOARD']['LOGIN'] ?? [];
                } else if (isset($request['type'])) {
                    $arrays = $_GET['__GLOBAL__']['__TYPES__']['__CUSTOMERS__']['LOGIN'] ?? [];
                }
            // GET ARRAYS


            // BUILD TYPES
                $types = [];
                foreach ($arrays as $key => $value) {
                    $types[] = $key;
                }
            // BUILD TYPES

            return $types;
        }
    // TYPES










    // LOGIN
        public function login(__LoginRequest $request): JsonResponse
        {
            $arr = [];
            $arr['types'] = $this->types($request);

            $arr = __LoginService::login($request, new Customers(), ['rootAuth', 'dashboardAuth'], $arr);
            return json_encode__($arr, $request);
        }
    // LOGIN










    // FORGET_PASSWORD
        private static $verified_at_seconds = 60;

        // CREATE CODE
            public function forget_password(Request $request): JsonResponse
            {                
                $arr = [];
                $arr['types'] = $this->types($request);

                $arr = __LoginService::forget_password($request, new Customers(), '__ForgetPasswordMail', $arr);
                return json_encode__($arr, $request);
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
            public function forget_password_verify_code(Request $request): JsonResponse
            {
                $arr = [];
                $arr['types'] = $this->types($request);

                $arr = __LoginService::forget_password_verify_code($request, new Customers());
                return json_encode__($arr, $request);
            }
        // VERIFY_CODE


        // UPDATE
            public function forget_password_update(Request $request): JsonResponse
            {
                $arr = __LoginService::forget_password_update($request, new Customers());

                if($arr['status'] == 200){
                    $arr['alert'] = 1;
                    $arr['msg'] = 'Senha alterada com sucesso!';
                }

                return json_encode__($arr, $request);
            }
        // UPDATE
    // FORGET_PASSWORD










    // LOGOUT
        public function logout(Request $request): JsonResponse
        {
            $arr = __LoginService::logout($request, ['rootAuth', 'dashboardAuth']);
            return json_encode__($arr, $request);
        }
    // LOGOUT

}