<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VagasRequest extends FormRequest
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
        'titulo'       => 'required|string',
        'descricao'    => 'required|string',
        'tipoContrato' => 'required|string',
        'local'        => 'required|string',
        'salario'      => ['required', 'numeric', 'regex:/^\d+(\.\d{1,2})?$/'], 
        'requisitos'   => 'required|string',
        'beneficios'   => 'required|string',
        'modeloTra' => ['required', function ($attribute, $result, $fail) {
            if (!is_array($result) && !is_string($result)) {
                $fail('O campo modeloTra deve ser uma string ou um array.');
            }
             }],
        'id'   =>  'nullable|int',
          
        ];
    }
     public function messages()
   {
   
    return [
        'titulo.required'        => 'O título é obrigatório.',
        'titulo.string'          => 'O título deve ser um texto.',

        'descricao.required'     => 'A descrição é obrigatória.',
        'descricao.string'       => 'A descrição deve ser um texto.',

        'tipoContrato.required'  => 'O tipo de contrato é obrigatório.',
        'tipoContrato.string'    => 'O tipo de contrato deve ser um texto.',

        'local.required'         => 'O local é obrigatório.',
        'local.string'           => 'O local deve ser um texto.',

        'salario.required'       => 'O salário é obrigatório.',
        'salario.numeric'        => 'O salário deve ser um número.',
        'salario.regex'          => 'O salário deve ter no máximo duas casas decimais. Exemplo: 1000.00',

        'requisitos.required'    => 'Os requisitos são obrigatórios.',
        'requisitos.string'      => 'Os requisitos devem ser um texto.',

        'beneficios.required'    => 'Os benefícios são obrigatórios.',
        'beneficios.string'      => 'Os benefícios devem ser um texto.',
   ];
}
}
