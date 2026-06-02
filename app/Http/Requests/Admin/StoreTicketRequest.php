<?php

namespace App\Http\Requests\Admin;

use App\Enums\Priority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'category_id'     => ['required', 'integer', 'exists:categories,id'],
            'priority'        => ['nullable', Rule::enum(Priority::class)],
            'description'     => ['required', 'string', 'max:2000'],
            'address'         => ['required', 'string', 'max:500'],
            'reference_point' => ['nullable', 'string', 'max:255'],
            'latitude'        => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'       => ['nullable', 'numeric', 'between:-180,180'],
            'citizen_name'    => ['required', 'string', 'max:255'],
            'citizen_email'   => ['required', 'email', 'max:255'],
            'citizen_phone'   => ['nullable', 'string', 'max:20'],
            'citizen_cpf'     => ['nullable', 'string', 'max:14'],
            'user_id'         => ['nullable', 'exists:users,id'],
            'photos'          => ['nullable', 'array', 'max:10'],
            'photos.*'        => ['image', 'max:10240'],
        ];
    }
}
