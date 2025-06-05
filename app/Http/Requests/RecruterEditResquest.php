<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecruterEditResquest extends FormRequest
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
             'phone' => ['required', 'string', 'regex:/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/'],

        ];
    }
      public function messages()
   {
   
    return [
       
       'nome.required' => 'O nome e obrigatorio',
       'nameEmpresa.required' => 'O Nome Empresa e obrigatorio',
       'email.required' => 'O email é obrigatório.',
       'email.email' => 'Informe um email válido.', 
      
   ];
}
}
