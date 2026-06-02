<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'comment'   => ['required', 'string', 'max:2000'],
            'is_public' => ['boolean'],
        ];
    }
}
