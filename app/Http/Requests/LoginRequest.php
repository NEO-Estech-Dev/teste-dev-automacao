<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            // '_token' => 'required|string',
            'passwordLogin' => 'required|string',
            'email' => 'required|email',
        ];
    }
    
   public function messages()
   {
   
    return [
       'email.required' => 'O email é obrigatório.',
       'email.email' => 'Informe um email válido.',
       'passwordLogin.required' => 'A senha é obrigatória.',
       'passwordLogin.min' => 'A senha deve ter no mínimo 8 caracteres.',
   ];
}
}
