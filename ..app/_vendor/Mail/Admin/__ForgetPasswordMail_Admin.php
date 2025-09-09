<?php

namespace Vendor\Mail\Admin;

use Illuminate\Http\Request;
use Vendor\Models\XSettings;

class __ForgetPasswordMail_Admin
{

    public static function get(Request $request, array $data): array
    {
        $XSettings = XSettings::get__(['name_site', 'email']);


        // SUBJECT / MESSAGE
            // SUBJECT
                $subject = 'Solicitação para alterar sua senha '.$XSettings->name_site;
            // SUBJECT


            // MESSAGE
                $message  = '';
                if (file_exists(DIR_P.'/img/logo.png')) {
                    $message .= '<div><img src="'.DIR.'/img/logo.png" style="max-height: 70px" /></div> ';
                }

                $message .= '  <div style="color:#333">
                                <div style="padding-top: 10px">Olá '.($data['query']['customers']->name ?? `usuário`).',</div>
                                <div style="padding-top: 40px; font-size: 20px; font-weight: 600; text-align: center">Código para autorizar a sua transação de alterar senha:</div>
                                <div style="padding-top: 10px; font-size: 24px; font-weight: bold; text-align: center">'.$data['token'].'</div>
                                <div style="padding-top: 40px">'.$XSettings->name_site.'</div>
                            </div>';
            // MESSAGE
        // SUBJECT / MESSAGE


        // RETURN
            return [
                'to' => $data['to'] ?? ($data['query']['customers']->email ?? ''),
                'subject' => $subject,
                'message' => $message,
            ];
        // RETURN

    }

}