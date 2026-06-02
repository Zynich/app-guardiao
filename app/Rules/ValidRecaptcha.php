<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ValidRecaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secretKey = config('services.recaptcha.secret_key');
        $minScore  = (float) config('services.recaptcha.min_score', 0.5);

        // Em testes ou se a chave não estiver configurada, pula a verificação
        if (app()->environment('testing') || empty($secretKey)) {
            return;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $secretKey,
                'response' => $value,
                'remoteip' => request()->ip(),
            ]);

            $body = $response->json();

            if (! ($body['success'] ?? false) || ($body['score'] ?? 0) < $minScore) {
                $fail('Verificação de segurança falhou. Tente novamente.');
            }
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA verification error: ' . $e->getMessage());
            // Falha silenciosa: em caso de erro de rede, não bloqueia o cidadão
        }
    }
}
