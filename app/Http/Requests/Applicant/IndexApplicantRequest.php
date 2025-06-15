<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;

class IndexApplicantRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'vacancies_id' => ['nullable', 'array'],
            'vacancies_id.*' => ['integer', 'exists:vacancies,id'],
            'order_by' => ['nullable', 'string', 'in:name,email'],
            'order_direction' => ['nullable', 'string', 'in:asc,desc'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
