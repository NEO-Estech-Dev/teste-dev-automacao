<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = User::where('email', $this->email)->first();
            if ($user) {
                if (! auth()->attempt($this->toArray())) {
                    $validator->errors()->add('email', 'Credenciais inválidas.');
                }
            } else {
                $validator->errors()->add('email', 'O usuário não existe.');
            }
        });
    }
}
