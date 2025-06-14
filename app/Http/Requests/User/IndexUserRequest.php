<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:recruiter,applicant'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'order_by' => ['nullable', 'string', 'in:name,email,type,created_at'],
            'order_direction' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
