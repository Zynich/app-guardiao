<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Rules\ValidCpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', Password::min(8)->letters()->numbers()],
            'role'         => ['required', Rule::enum(UserRole::class)],
            'badge_number' => ['nullable', 'string', 'max:20', 'unique:users,badge_number'],
            'phone'        => ['nullable', 'string', 'max:20'],
            'cpf'          => ['nullable', 'string', 'max:14', 'unique:users,cpf', new ValidCpf()],
            'is_active'    => ['boolean'],
        ];
    }
}
