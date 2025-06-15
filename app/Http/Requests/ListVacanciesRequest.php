<?php

namespace App\Http\Requests;

use App\Enums\VacancyType;
use Illuminate\Foundation\Http\FormRequest;

class ListVacanciesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'type' => 'nullable|string|in:' . implode(',', VacancyType::values()),
            'recruiter_id' => 'nullable|integer',
            'salary' => 'nullable|integer',
            'status' => 'nullable|string|in:active,inactive',
            'paginate' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:title,description,company_name,type,salary,status,created_at',
            'order' => 'nullable|string|in:asc,desc',
        ];
    }
}
