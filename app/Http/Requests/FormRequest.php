<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class CustomFormRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize()
    {
        return true; // ou lógica de permissão
    }

    public function rules()
    {
        return [
            'arquivoCv' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'arquivoCv.required' => 'O arquivo é obrigatório.',
            'arquivoCv.file' => 'O arquivo enviado é inválido.',
            'arquivoCv.mimes' => 'O arquivo deve ser um PDF ou Word (doc, docx).',
            'arquivoCv.max' => 'O arquivo não pode ter mais que 2MB.',
        ];
    }
}
