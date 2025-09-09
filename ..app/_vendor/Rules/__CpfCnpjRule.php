<?php

namespace Vendor\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class __CpfCnpjRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (strlen($value) <= 14) {
            $cpfRule = new __CpfRule();
            $cpfRule->validate($attribute, $value, $fail);
        } else {
            $cnpjRule = new __CnpjRule();
            $cnpjRule->validate($attribute, $value, $fail);
        }
    }
}