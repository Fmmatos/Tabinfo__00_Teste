<?php

namespace Vendor\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class __CpfRule implements ValidationRule
{
    public function __construct()
    {
        //
    }

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $cpf = strtolower($value);

        $s = "";
        for ($x=1; $x<=strlen($cpf); $x=$x+1) {
            $ch=substr($cpf,$x-1,1);
            if (ord($ch)>=48 && ord($ch)<=57) {
                $s=$s.$ch;
            }
        }

        $cpf = $s;
        if ($cpf == '') {
            $fail('Preencha o CPF!');

        } else if(strlen($cpf) > 11) {
            $fail('CPF inválido, insira outro CPF!');

        } else if($cpf=="00000000000" || $cpf=="11111111111" || $cpf=="22222222222" || $cpf=="33333333333" || $cpf=="44444444444" || $cpf=="55555555555" || $cpf=="66666666666" || $cpf=="77777777777" || $cpf=="88888888888" || $cpf=="99999999999") {
            $fail('CPF inválido, insira outro CPF!');

        } else {
            $Numero[1]=intval(substr($cpf,1-1,1));
            $Numero[2]=intval(substr($cpf,2-1,1));
            $Numero[3]=intval(substr($cpf,3-1,1));
            $Numero[4]=intval(substr($cpf,4-1,1));
            $Numero[5]=intval(substr($cpf,5-1,1));
            $Numero[6]=intval(substr($cpf,6-1,1));
            $Numero[7]=intval(substr($cpf,7-1,1));
            $Numero[8]=intval(substr($cpf,8-1,1));
            $Numero[9]=intval(substr($cpf,9-1,1));
            $Numero[10]=intval(substr($cpf,10-1,1));
            $Numero[11]=intval(substr($cpf,11-1,1));
            
            $soma=10*$Numero[1]+9*$Numero[2]+8*$Numero[3]+7*$Numero[4]+6*$Numero[5]+5*
            $Numero[6]+4*$Numero[7]+3*$Numero[8]+2*$Numero[9];
            $soma=$soma-(11*(intval($soma/11)));
        
            if ($soma==0 || $soma==1) {
                $resultado1=0;
            } else {
                $resultado1=11-$soma;
            }
        
            if ($resultado1==$Numero[10]) {
                $soma=$Numero[1]*11+$Numero[2]*10+$Numero[3]*9+$Numero[4]*8+$Numero[5]*7+$Numero[6]*6+$Numero[7]*5+
                $Numero[8]*4+$Numero[9]*3+$Numero[10]*2;
                $soma=$soma-(11*(intval($soma/11)));
        
                if ($soma==0 || $soma==1) {
                    $resultado2=0;
                } else {
                    $resultado2=11-$soma;
                }
                if ($resultado2==$Numero[11]) {
                    // CPF válido
                } else {
                    $fail('CPF inválido, insira outro CPF!');
                }
            } else {
                $fail('CPF inválido, insira outro CPF!');
            }
        }
    }
}
