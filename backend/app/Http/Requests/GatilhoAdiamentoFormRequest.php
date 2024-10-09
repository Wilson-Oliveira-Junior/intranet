<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GatilhoAdiamentoFormRequest extends FormRequest
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
            //
            'data_adiamento' => 'required',
            'motivo' => 'required|min:15'
        ];
    }

    public function messages()
    {
        return [
            'data_adiamento.required' => 'O Campo data adiamento é obrigatório, por favor preencha corretamente.',
            'motivo.required' => 'O Campo motivo é obrigatório, por favor preencha corretamente.',
            'motivo.min' => 'O Campo motivo tem que ter no mínimo 15 caracteres, por favor preencha corretamente.'
        ];
    }
}
