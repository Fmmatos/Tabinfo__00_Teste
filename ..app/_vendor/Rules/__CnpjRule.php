<?php

namespace Vendor\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class __CnpjRule implements ValidationRule
{
    public function __construct()
    {
        //
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $cnpj = strtolower($value);

        $s = "";
        for ($x=1; $x<=strlen($cnpj); $x=$x+1) {
            $ch=substr($cnpj,$x-1,1);
            if (ord($ch)>=48 && ord($ch)<=57) {
                $s=$s.$ch;
            }
        }
        
        $cnpj = $s;
        if ($cnpj == '') {
            $fail('Preencha o CNPJ!');

        } else if($cnpj=="00000000000000" || $cnpj=="11111111111111" || $cnpj=="22222222222222" || $cnpj=="33333333333333" || $cnpj=="44444444444444" || $cnpj=="55555555555555" || $cnpj=="66666666666666" || $cnpj=="77777777777777" || $cnpj=="88888888888888" || $cnpj=="99999999999999") {
            $fail('CNPJ inválido, insira outro CNPJ!');
        } else {
            
            if (strlen($cnpj) <> 14) {
                $fail('CNPJ inválido, insira outro CNPJ!');
    
            } else {
                $soma = 0;
                
                $soma += ($cnpj[0] * 5);
                $soma += ($cnpj[1] * 4);
                $soma += ($cnpj[2] * 3);
                $soma += ($cnpj[3] * 2);
                $soma += ($cnpj[4] * 9); 
                $soma += ($cnpj[5] * 8);
                $soma += ($cnpj[6] * 7);
                $soma += ($cnpj[7] * 6);
                $soma += ($cnpj[8] * 5);
                $soma += ($cnpj[9] * 4);
                $soma += ($cnpj[10] * 3);
                $soma += ($cnpj[11] * 2); 
                
                $d1 = $soma % 11; 
                $d1 = $d1 < 2 ? 0 : 11 - $d1; 
                
                $soma = 0;
                $soma += ($cnpj[0] * 6); 
                $soma += ($cnpj[1] * 5);
                $soma += ($cnpj[2] * 4);
                $soma += ($cnpj[3] * 3);
                $soma += ($cnpj[4] * 2);
                $soma += ($cnpj[5] * 9);
                $soma += ($cnpj[6] * 8);
                $soma += ($cnpj[7] * 7);
                $soma += ($cnpj[8] * 6);
                $soma += ($cnpj[9] * 5);
                $soma += ($cnpj[10] * 4);
                $soma += ($cnpj[11] * 3);
                $soma += ($cnpj[12] * 2); 
                
                
                $d2 = $soma % 11; 
                $d2 = $d2 < 2 ? 0 : 11 - $d2;
                
                
                if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
                    // CNPJ válido
                } else {
                    $fail('CNPJ inválido, insira outro CNPJ!');
                }
            }
        }
    }
}
