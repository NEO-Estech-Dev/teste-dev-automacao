<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'type' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->type) {
                if ($this->type !== 'recrutador' && $this->type !== 'candidato') {
                    $validator->errors()->add('type', 'O tipo deve ser recrutador ou candidato.');
                }
            }
        });
    }
}
