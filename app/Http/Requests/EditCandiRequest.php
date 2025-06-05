<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCandiRequest extends FormRequest
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
            'id' => 'required|int',
           'nome'=> 'required|string',
            'email' => 'required|email',
            'phone' => ['required', 'string', 'regex:/^\(?\d{2}\)?[\s-]?\d{4,5}-?\d{4}$/'],
            'estado' => 'required|string',
            'cidade' => 'required|string',
            'nameCurso' => 'required|string',
            'nameFormacao' => 'required|string',
        ];
    }
          public function messages()
   {
   
    return [
      
         'id.required' => 'O id e obrigatorio',
         'nome.required' => 'O Nome e obrigatorio',
         'email.required' => 'O Email e obrigatorio',
         'phone.required' => 'O telefone e obrigatorio',
         'estado.required' => 'O estado e obrigatorio',
         'cidade.required' => 'O Cidade e obrigatorio',
         'nameCurso.required' => 'O Curso e obrigatorio',
         'nameFormacao.required' => 'O Formaço e obrigatorio',
    ];
   
 } 
}
