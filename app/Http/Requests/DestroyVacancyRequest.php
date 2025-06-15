<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestroyVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isRecruiter();
    }
}
