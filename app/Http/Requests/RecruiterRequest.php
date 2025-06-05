<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecruiterRequest extends FormRequest
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

            'nome' => 'required|string',
           'nameEmpresa'=> 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string'
        ];
    }
      public function messages()
   {
   
    return [
       
       'recruiter.required' => 'O nome e obrigatorio',
       'nameEmpresa.required' => 'O Nome Empresa e obrigatorio',
       'email.required' => 'O email é obrigatório.',
       'email.email' => 'Informe um email válido.', 
       'password.required' => 'A senha é obrigatória.',
       'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
   ];
}
}
