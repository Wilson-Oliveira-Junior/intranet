<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DataComemorativaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authorize = $this->user()->can('adicionar_data_comemorativa');
        return $authorize;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|min:3|unique:tbDatasComemorativas',
            'data_comemorativa' => 'required|date|date_format:Y-m-d',
        ];
    }

    public function messages(){
        return [
            'nome.required' => 'O Campo Data Comemorativa precisa ser preenchido corretamente.',
            'nome.min' => 'O Campo Data Comemorativa precisa ter pelo menos 3 caracteres.',
            'nome.unique' => 'Já existe uma data comemorativa com este nome.',
            'data_comemorativa.required' => 'A Data é obrigatória, preencha corretamente por favor.',
            'data_comemorativa.date_format' => 'A Data colocada esta incorreta, corrija por favor.',
        ];
    }
}
