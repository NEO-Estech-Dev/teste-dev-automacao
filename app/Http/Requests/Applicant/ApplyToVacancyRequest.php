<?php

namespace App\Http\Requests\Applicant;

use App\Models\Vacancy;
use Illuminate\Foundation\Http\FormRequest;

class ApplyToVacancyRequest extends FormRequest
{
    public function authorize()
    {
        return ! auth()->user()->isRecruiter();
    }

    public function rules(): array
    {
        return [
            'vacancy_id' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $vacancy = Vacancy::find($this->vacancy_id);
            if ($vacancy) {
                if ($vacancy->status === 'closed') {
                    $validator->errors()->add('vacancy_id', 'A vaga selecionada está fechada.');
                } elseif ($vacancy->status === 'paused') {
                    $validator->errors()->add('vacancy_id', 'A vaga selecionada está pausada.');
                } elseif ($vacancy->users()->where('user_id', auth()->id())->exists()) {
                    $validator->errors()->add('vacancy_id', 'Você já se candidatou a esta vaga.');
                }
            } else {
                $validator->errors()->add('vacancy_id', 'A vaga selecionada não existe.');
            }
        });
    }
}
