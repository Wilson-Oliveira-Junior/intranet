<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GatilhoComentarioProjetoFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'comentario' => 'required|min:15',
        ];
    }

    public function messages()
    {
        return [
            'comentario.required' => 'O Campo observação é obrigatório seu preenchimento.',
            'comentario.min' => 'O Campo observação tem um mínimo de 15 caracteres.',
        ];
    }
}
