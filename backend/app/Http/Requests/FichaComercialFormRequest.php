<?php

namespace App\Http\Requests;

use App\Rules\Cnpj;
use Illuminate\Foundation\Http\FormRequest;

class FichaComercialFormRequest extends FormRequest
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
            'cnpj_cpf' => ['required', 'min:11', 'max:14', new Cnpj],
            'razao_social' => 'required|min:10',
            'nome_fantasia' => 'required|min:5',
            'cep' => 'required',
            'endereco' => 'required|max:164',
            'bairro' => 'required|max:64',
            'cidade' => 'required|max:64',
            'estado' => 'required|in:AC,AL,AP,AM,BA,CE,DF,ES,GO,MA,MT,MS,MG,PA,PB,PR,PE,PI,RJ,RN,RS,RO,RR,SC,SP,SE,TO',
            'numero' => 'required|max:12',
            'dia_boleto' => 'required|not_in:--',
            'nota_fiscal' => 'required|not_in:--',
        ];
    }

    public function messages()
    {
        return [
                'cnpj_cpf.required' => 'O campo CNPJ/CPF precisa ser preenchido corretamente.',
                'cnpj_cpf.max' => 'O campo CNPJ/CPF precisa ter no mínimo :min caracteres.',
                'cnpj_cpf.min' => 'O campo CNPJ/CPF precisa ter no máximo :max caracteres.',
                'razao_social.required' => 'O campo Razão Social precisa ser preenchido corretamente',
                'razao_social.min' => 'O campo Razão Social precisa ter no máximo :max caracteres.',
                'estado.required' => 'O campo Estado precisa ser preenchido corretamente',
                'estado.in' => 'O campo estado só aceita unidades federativas do Brasil. Ex: SP, RJ',
                'dia_boleto.required' => 'O campo Melhor dia para boleto precisa ser preenchido corretamente',
                'dia_boleto.not_in' => 'Escolha um dia para boleto corretamente.',
                'nota_fiscal.required' => 'O campo Nota Fiscal precisa ser preenchido corretamente',
                'nota_fiscal.not_in' => 'Escolha uma opção para nota fiscal.',
            ];
    }
}
