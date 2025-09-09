<?php

namespace Vendor\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Vendor\Mail\__Mail;
use Vendor\Models\Admin\Users_Admin;
use Vendor\Requests\Admin\__LoginRequest_Admin;
use Vendor\Rules\__PasswordRule;
use Vendor\Services\__LoginService;

class __LoginController_Admin
{

    // INDEX
        public function index(Request $request): JsonResponse
        {
            $arr = [];

            $arr['status'] = 200;
            return json_encode__($arr, $request);
        }
    // INDEX










    // LOGIN
        public function login(__LoginRequest_Admin $request)
        {
            $arr = __LoginService::login($request, new Users_Admin(), ['adminAuth']);
            return json_encode__($arr, $request);
        }
    // LOGIN










    // FORGET_PASSWORD
        // CREATE CODE
            public function forget_password(Request $request): JsonResponse
            {
                $arr = __LoginService::forget_password($request, new Users_Admin(), '__ForgetPasswordMail_Admin');
                return json_encode__($arr, $request);
            }
        // CREATE CODE


        // RESEND_CODE
            public function forget_password_resend_code(Request $request): JsonResponse
            {
                $arr = __LoginService::forget_password_resend_code($request, new Users_Admin(), '__ForgetPasswordMail_Admin');
                return json_encode__($arr, $request);
            }
        // RESEND_CODE


        // VERIFY_CODE
            public function forget_password_verify_code(Request $request): JsonResponse
            {
                $arr = __LoginService::forget_password_verify_code($request, new Users_Admin());
                return json_encode__($arr, $request);
            }
        // VERIFY_CODE


        // UPDATE
            public function forget_password_update(Request $request): JsonResponse
            {
                $arr = __LoginService::forget_password_update($request, new Users_Admin());
                return json_encode__($arr, $request);
            }
        // UPDATE
    // FORGET_PASSWORD










    // LOGOUT
        public function logout(Request $request): JsonResponse
        {
            $arr = __LoginService::logout($request, ['adminAuth']);
            return json_encode__($arr, $request);
        }
    // LOGOUT

}