<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVacancyRequest extends FormRequest
{
    //    public function authorize(): bool
    //    {
    //        return $this->user()->isRecruiter();
    //    }
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'in:open,closed,paused'],
            'type' => ['nullable', 'string', 'in:employee,independent_contract,freelancer'],
            'recruiter_id' => ['nullable', 'exists:users,id,type,recruiter'],
        ];
    }
}
