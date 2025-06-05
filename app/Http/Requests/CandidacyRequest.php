<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CandidacyRequest extends FormRequest
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
            'idVaga' => 'required|int',
            
        ];
    }
        public function messages()
   {
   
    return [
      
         'id.required' => 'O id e obrigatorio',
         'idVaga.required' => 'O id da vaga e obrigatorio',
    ];
   
 } 
}
