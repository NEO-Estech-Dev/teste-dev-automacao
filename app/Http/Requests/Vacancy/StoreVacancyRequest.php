<?php

namespace App\Http\Requests\Vacancy;

use Illuminate\Foundation\Http\FormRequest;

class StoreVacancyRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isRecruiter();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'salary' => ['required', 'numeric', 'min:0'],
            'type' => ['required', 'string', 'in:employee,independent_contractor,freelancer'],
            'status' => ['required', 'string', 'in:open,closed,paused'],
        ];
    }
}
