<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'type' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if($this->type){
                if ($this->type !== 'recrutador' && $this->type !== 'candidato') {
                    $validator->errors()->add('type', 'O tipo deve ser recrutador ou candidato.');
                }
            }
        });
    }
}
