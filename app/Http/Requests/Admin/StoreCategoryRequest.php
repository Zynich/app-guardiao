<?php

namespace App\Http\Requests\Admin;

use App\Enums\Priority;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:30', 'unique:categories,name'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority'    => ['required', Rule::enum(Priority::class)],
            'sla_hours'   => ['required', 'integer', 'min:1', 'max:8760'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
            'is_active'   => ['boolean'],
        ];
    }
}
