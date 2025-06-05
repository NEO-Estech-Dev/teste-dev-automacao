<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PauseVancyRequest extends FormRequest
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
        return  [

            'id' => 'required|int',
            'idvaga'=> 'required|int',
            'funcao' => 'nullable|int'
          
        ];
    }
      public function messages()
   {
   
    return [
       
       'id.required' => 'O id e obrigatorio',
       'idvaga.required' => 'O idvaga é obrigatório.',
      
   ];
   
  }
}
