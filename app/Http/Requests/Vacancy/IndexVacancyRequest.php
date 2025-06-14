<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class IndexVacancyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'type' => ['nullable', 'string', 'in:employee,independent_contractor,freelancer'],
            'status' => ['nullable', 'string', 'in:open,closed,paused,open'],
            'recruiter_id' => ['nullable', 'integer', 'exists:users,id,type,recruiter'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
            'order_by' => ['nullable', 'string', 'in:title,description,salary,type,status,recruiter_id,created_at,updated_at'],
            'order_direction' => ['nullable', 'string', 'in:asc,desc'],
        ];
    }
}
