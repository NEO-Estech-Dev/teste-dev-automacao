<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListCandidatesRequest extends FormRequest
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
            'paginate' => 'nullable|integer|min:1',
            'status' => 'nullable|string',
            'vacancy_id' => 'nullable|integer',
            'user_id' => 'nullable|integer',
            'candidate_name' => 'nullable|string',
            'vacancy_title' => 'nullable|string',
            'order_by' => 'nullable|string',
            'order' => 'nullable|string|in:asc,desc',
        ];
    }
}
