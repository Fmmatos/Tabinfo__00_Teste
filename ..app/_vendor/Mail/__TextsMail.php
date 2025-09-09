<?php

namespace Vendor\Mail;

use Illuminate\Http\Request;
use Root\Mail\TextsMail;
use Vendor\Models\XSettings;

class __TextsMail
{

    public static function get(Request|array $request, array $data): array
    {
        $XSettings = XSettings::get__(['name_site', 'email']);


        // TEXTS / VARIABLES
            // TEXTS
                $id = TextsMail::id($request, $data);

                $Texts = TextsMail::texts($request, $data, $id);
            // TEXTS



            // VARIABLES
                $variables = TextsMail::variables($request, $Texts, $data, $XSettings);
            // VARIABLES
        // TEXTS / VARIABLES





        // RETURN
            if (!isset($Texts->id)) {
                return $data;
            }

            return [
                'to' => $data['to'] ?? ($data['query']['customers']->email ?? ''),
                'from' => $data['from'] ?? $XSettings->email,
                'subject' => self::treatment($Texts->name, $variables),
                'message' => self::treatment($Texts->editor, $variables),
            ];
        // RETURN

    }




















    // TREATMENT
        public static function treatment(string $txt, array $variables): string
        {
            foreach ($variables as $key => $value) {
                $value = replace('&nbsp;', ' ', $value);
                $txt = replace('{'.$key.'}', $value, $txt);
            }
            return $txt;
        }
    // TREATMENT

}