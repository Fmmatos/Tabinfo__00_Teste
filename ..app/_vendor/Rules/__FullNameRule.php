<?php

namespace Vendor\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class __FullNameRule implements ValidationRule
{
    private $name;

    public function __construct(string $name='nome')
    {
        $this->name = $name;
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $name = fullname(trim($value));

        $ex = explode(' ', trim($value));
        if (mb_strlen($name)>2 && count($ex) > 2) {
            // Nome válido
        } else {
            $surname = fullname(trim($value), 1);
            if (mb_strlen($name)<=2 || mb_strlen($surname)<=2) {
                $fail('Preencha o '.$this->name.' completo!');
            }
        }
    }
}
