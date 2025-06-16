<?php

namespace App\Http\Requests;

use App\Enums\UserType;
use App\Enums\VacancyType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateVacancyRequest extends FormRequest
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
            'type' => 'nullable|string|in:' . implode(',', VacancyType::values()),
            'description' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'salary' => 'nullable|integer',
        ];
    }
}
