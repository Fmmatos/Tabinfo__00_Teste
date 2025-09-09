<?php

namespace Vendor\Mail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Vendor\Mail\__TextsMail;
use Vendor\Models\XSettings;

class __Mail
{

    // SEND__
        public static function send(Request|array $request, string $type, array $data, ?object $smtp = null): int
        {

            // SIMPLE
                if ($type == 'simple') {
                    // SEND_EMAIL
                        return self::send_email($data['to'], $data['subject'], $data['message'], $data['from']??'', $data['from_name']??'', $smtp);
                    // SEND_EMAIL

                    // EXEMPLE
                        // return __Mail::send($request, 'simple', [
                        //     'to'=> $to,
                        //     'subject'=> $subject,
                        //     'message'=> $message,
                        //     'from'=> $from,
                        // ]);
                    // EXEMPLE
                }
            // SIMPLE





            // CLASS
                else if($type == 'class' && isset($data['class'])) {
                    $className = $data['class'];
                    if (strpos($className, '\\') === false) {
                        if (class_exists('Vendor\\Mail\\Admin\\' . $className)) {
                            $className = 'Vendor\\Mail\\Admin\\' . $className;
                        } elseif (class_exists('Vendor\\Mail\\Root\\' . $className)) {
                            $className = 'Vendor\\Mail\\Root\\' . $className;
                        } elseif (class_exists('Root\\Mail\\' . $className)) {
                            $className = 'Root\\Mail\\' . $className;
                        }
                    }
                    $class = new $className();
                    if (method_exists($class, 'get')) {
                        $data = $class->get($request, $data);
                    }

                    // DEFAULT
                        $data['to'] = $data['to'] ?? XSettings::get__(['email'])->email;
                        $data['from'] = $data['from'] ?? XSettings::get__(['email'])->email;
                    // DEFAULT

                    // SEND_EMAIL
                        return self::send_email($data['to'], $data['subject'], $data['message'], $data['from']??'', $data['from_name']??'', $smtp);
                    // SEND_EMAIL


                    // EXEMPLE
                        // __Mail::send($request, 'class', [
                        //     'class' => '__ForgetPasswordMail_Admin',
                        //     'query' => ['customers' => $value],
                        //     'rand' => $rand
                        // ]);
                    // EXEMPLE
                }
            // CLASS





            // TEXTS
                else if($type == 'texts') {
                    $data = __TextsMail::get($request, $data);

                    // SEND_EMAIL
                        return self::send_email($data['to'], $data['subject'], $data['message'], $data['from']??'', $data['from_name']??'', $smtp);
                    // SEND_EMAIL

                    // EXEMPLE
                        // __Mail::send($request, 'texts', [
                        //     'table' => 'customers',
                        //     'type' => $request['type'],
                        //     'query' => ['customers' => $value]
                        // ]);
                    // EXEMPLE
                }
            // TEXTS


            return 0;
        }
    // SEND__










    // SEND_EMAIL
        public static function send_email(string $to, string $subject, string $message, string $from = '', string $from_name = '', ?object $smtp = null): int
        {
            // SEND
                $return = 0;

                if (isset($subject) && $subject) {
                    if (!LOCALHOST || LOCALHOST_TESTE_MAIL || isset($_GET['MAIL_TESTE'])) {

                        // MAIL_SEND__SMTP
                            if (!$return) {
                                $return = self::MAIL_SEND__SMTP($to, $subject, $message, $from, $from_name, $smtp);
                            }
                        // MAIL_SEND__SMTP


                        // MAIL_SEND__PHP
                            if (!$return) {
                                // $return = self::MAIL_SEND__PHP($to, $subject, $message, $from, $from_name);
                            }
                        // MAIL_SEND__PHP

                    }

                    // TESTE MAIL
                        if(LOCALHOST && TESTE_MAIL){
                            $return = 1;
                        }
                    // TESTE MAIL

                    if (LOCALHOST || PROG || isset($_GET['MAIL_TESTE'])) {
                        GET_pre_fixed_set('send: '.$return);
                        GET_pre_fixed_set('to: '.$to);
                        GET_pre_fixed_set('from: '.$from);
                        GET_pre_fixed_set('subject: '.$subject);
                        GET_pre_fixed_set('message: '.$message);
                    }

                } else {
                    GET_pre_fixed_set('email: no subject');
                }
            // SEND

            return $return;
        }
    // SEND_EMAIL




















    // MAIL_SEND__SMTP
        public static function MAIL_SEND__SMTP(string $to, string $subject, string $message, string $from = '', string $from_name = '', ?object $smtp = null): int
        {
            $return = 0;

            // STMP
                if (!empty($smtp->smtp_smtp) && !empty($smtp->smtp_email) && !empty($smtp->smtp_password) && !empty($smtp->name_site)) {
                    $settings = $smtp;
                    $settings->smtp_active = 1;

                } else {
                    $settings = XSettings::get__(['smtp_active', 'smtp_smtp', 'smtp_ssl', 'smtp_email', 'smtp_password', 'smtp_email', 'name_site']);
                }

                // SETTINGS
                    if ($settings->smtp_active) {
                        $data_smtp = [
                            'driver'        => 'smtp',
                            'host'          => $settings->smtp_smtp,
                            'port'          => !empty($settings->smtp_ssl) ? 465 : 587,
                            'encryption'    => !empty($settings->smtp_ssl) ? 'ssl' : 'tls',
                            'username'      => $settings->smtp_email,
                            'password'      => $settings->smtp_password,
                            'from'          => [
                                'address'   => $settings->smtp_email,
                                'name'      => $settings->name_site
                            ]
                        ];
                        Config::set('mail', $data_smtp);
                    }
                // SETTINGS
            // STMP


            // SEND
                if ($settings->smtp_active) {
                    if(empty($from)) {
                        $from = $settings->smtp_email;
                        $from_name = $settings->name_site;
                    }
                    $from = $settings->smtp_email;
                    $from_name = $settings->name_site;

                    try {
                        Mail::html($message, function ($item) use ($from, $from_name, $to, $subject) {
                            $item
                            ->to($to)
                            ->from($from, $from_name??$from)
                            // ->replyTo('suporte@loja.com', 'Suporte')    // Email que será enviado se o destinatário responder
                            // ->cc('fmmatos3@gmail.com')                     // Copia
                            // ->bcc('fmmatos3@gmail.com')                   // Copia oculta
                            // ->attach('caminho/do/arquivo.pdf', [        // Anexo
                            //     'as' => 'nome_do_arquivo.pdf',
                            //     'mime' => 'application/pdf',
                            // ])
                            ->subject($subject);
                        });
                        $return = 99;

                    } catch (\Throwable $th) {
                        if (isset($_GET['MAIL_TESTE'])) {
                            pre($th);
                        }
                    }
                }
            // SEND

            return $return;
        }
    // MAIL_SEND__SMTP















    // MAIL_SEND__PHP
        public static function MAIL_SEND__PHP(string $to, string $subject, string $message, string $from, string $from_name): int
        {
            $return = 0;

            // SETTINGS
                $headers  = "MIME-Version: 1.1 \n";
                $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
                $headers .= "From: ".$from_name." <".$from."> \n";
                $headers .= 'X-Mailer: PHP/' . phpversion();
            // SETTINGS


            // SEND
                try {
                    if (mail($to, $subject, $message, $headers)) {
                        $return = 1;
                    }

                } catch (\Throwable $th) { }
            // SEND

            return $return;
        }
    // MAIL_SEND__PHP

}