<?php

namespace App\Http\Requests\Portal;

use App\Rules\ValidRecaptcha;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'     => ['required', 'integer', 'exists:categories,id'],
            'description'     => ['required', 'string', 'max:1000'],
            'address'         => ['required', 'string', 'max:500'],
            'reference_point' => ['nullable', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'citizen_name'    => ['required', 'string', 'max:255'],
            'citizen_email'   => ['required', 'email', 'max:255'],
            'citizen_phone'   => ['nullable', 'string', 'max:20'],
            'citizen_cpf'     => ['nullable', 'string', 'max:14'],
            'photos'          => ['nullable', 'array', 'max:5'],
            'photos.*'        => ['image', 'max:5120'],
            'recaptcha_token' => ['nullable', 'string', new ValidRecaptcha()],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required'   => 'Selecione uma categoria para o problema.',
            'category_id.exists'     => 'Categoria inválida.',
            'description.required'   => 'Descreva o problema com mais detalhes.',
            'description.max'        => 'A descrição deve ter no máximo 1000 caracteres.',
            'address.required'       => 'Informe o endereço do problema.',
            'citizen_name.required'  => 'Informe seu nome completo.',
            'citizen_email.required' => 'Informe seu e-mail para receber o protocolo.',
            'citizen_email.email'    => 'Informe um e-mail válido.',
            'photos.max'             => 'Envie no máximo 5 fotos.',
            'photos.*.image'         => 'Apenas imagens são permitidas (JPG, PNG, etc).',
            'photos.*.max'           => 'Cada imagem deve ter no máximo 5MB.',
        ];
    }
}
