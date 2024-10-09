<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PautaObservacaoFormRequest extends FormRequest
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
            'observacao' => 'required|min:15',
        ];
    }

    public function messages()
    {
        return [
            'observacao.required' => 'O Campo observação é obrigatório seu preenchimento.',
            'observacao.min' => 'O Campo observação tem um mínimo de 15 caracteres.',
        ];
    }
}
