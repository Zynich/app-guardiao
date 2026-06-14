<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/\D/', '', $value);

        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $fail('CPF inválido.');
            return;
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (10 - $i);
        }
        $d1 = ($sum * 10) % 11;
        if ($d1 >= 10) $d1 = 0;

        $sum = 0;
        for ($i = 0; $i < 10; $i++) {
            $sum += intval($cpf[$i]) * (11 - $i);
        }
        $d2 = ($sum * 10) % 11;
        if ($d2 >= 10) $d2 = 0;

        if ($d1 !== intval($cpf[9]) || $d2 !== intval($cpf[10])) {
            $fail('CPF inválido.');
        }
    }
}
