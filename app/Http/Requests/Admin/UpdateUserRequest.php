<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use App\Rules\ValidCpf;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password'     => ['nullable', Password::min(8)->letters()->numbers()],
            'role'         => ['required', Rule::enum(UserRole::class)],
            'badge_number' => ['nullable', 'string', 'max:20', Rule::unique('users', 'badge_number')->ignore($userId)],
            'phone'        => ['nullable', 'string', 'max:20'],
            'cpf'          => ['nullable', 'string', 'max:14', Rule::unique('users', 'cpf')->ignore($userId), new ValidCpf()],
            'is_active'    => ['boolean'],
        ];
    }
}
