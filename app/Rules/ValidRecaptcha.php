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

            // Só bloqueia quando a verificação foi bem-sucedida mas o score indica bot.
            // success: false (token expirado, duplicado, erro de rede da API) → passa sem bloquear.
            if (($body['success'] ?? false) && ($body['score'] ?? 1.0) < $minScore) {
                $fail('Verificação de segurança falhou. Tente novamente.');
            }

            if (! ($body['success'] ?? false)) {
                Log::warning('reCAPTCHA: success=false', ['error-codes' => $body['error-codes'] ?? []]);
            }
        } catch (\Throwable $e) {
            Log::warning('reCAPTCHA verification error: ' . $e->getMessage());
            // Falha silenciosa: em caso de erro de rede, não bloqueia o cidadão
        }
    }
}
