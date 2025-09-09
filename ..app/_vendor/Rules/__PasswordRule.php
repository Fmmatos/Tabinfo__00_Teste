<?php

namespace Vendor\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Request;

class __PasswordRule implements ValidationRule
{
    private $request;

    public function __construct(array|Request $request)
    {
        $this->request = $request;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {

        // MORE THAN 8 CHAR
            if (mb_strlen($value) < 8) {
                $fail('A Senha deve conter mais de 8 caracteres!');
                return;
            }
        // MORE THAN 8 CHAR

        // AT LEAST ONE NUMBER
            if (!preg_match("/[0-9]/i", $value)) {
                $fail('A Senha deve conter pelo menos 1 número!');
                return;
            }
        // AT LEAST ONE NUMBER

        // AT LEAST ONE LETTER
            if (!preg_match("/[A-Z]/i", $value)) {
                $fail('A Senha deve conter pelo menos 1 letra!');
                return;
            }
        // AT LEAST ONE LETTER

        // AT LEAST ONE ESPECIAL CHARACTER
            if (!preg_match("/[^a-zA-Z0-9\s]/i", $value)) {
                $fail('A Senha deve conter pelo menos 1 caracter especial!');
                return;
            }
        // AT LEAST ONE ESPECIAL CHARACTER

        // PASSWORD_CONFIRMATION
            if (isset($this->request['password_confirmation']) && $value != $this->request['password_confirmation']) {
                $fail('A Senha e a Confirmação da Senha não são Iguais!');
                return;
            }
        // PASSWORD_CONFIRMATION
    }

    public static function admin__passes(string $attribute, string $value, array|Request $request): string
    {
        $instance = new self($request);
        $instance->validate($attribute, $value, function($msg) {
            return $msg;
        });

        return '';
    }
}
