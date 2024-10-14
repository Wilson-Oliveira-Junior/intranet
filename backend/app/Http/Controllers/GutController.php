<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SetorUsuario;
use App\Tarefa;
use App\User;
use Illuminate\Support\Facades\Auth;

class GutController extends Controller
{
    //
    public function index($idequipe){

        if($idequipe == "" || is_null($idequipe)){
            $idequipe = auth()->user()->setor;
        }

        $equipe = SetorUsuario::find($idequipe);
        $setores = SetorUsuario::query()->select('id','nome')->orderBy('nome', 'ASC')->get();

        //dd($setores);

        return view('backend.tarefa.gut.index', compact('equipe', 'setores'));
    }

    public function listarTarefas($idequipe){

        $usuarios = User::where('ativo',1)->where('setor', $idequipe)->select('id')->get()->toArray();
        //dd($usuarios);
        $arrTarefas = Tarefa::with('responsavel', 'statusTarefa')->where('status', 'Producao')
            ->where(function($q) use($usuarios, $idequipe){
                $q->whereIn('id_responsavel', $usuarios)
                    ->orWhere('id_equipe', $idequipe);
            })
            ->orderBy('tarefa_ordem', 'DESC')
            ->orderBy('id', 'ASC')
            ->get();
        //dd(count($arrTarefas));

        return view('backend.tarefa.gut.tarefas', compact('arrTarefas'));
    }

    public function salvarPontuacao(Request $request){
        $dados = $request->all();

        //dd($dados);

        $tarefa = Tarefa::find($dados['idtarefa']);
        if($dados['pontuacao']>0){
            $tarefa->gravidade = $dados['gravidade'];
            $tarefa->urgencia = $dados['urgencia'];
            $tarefa->tendencia = $dados['tendencia'];
            $tarefa->tarefa_ordem = $dados['pontuacao'];
        }
        if(!is_null($dados['datadesejada'])){
            $tarefa->data_desejada = $dados['datadesejada'];
        }
        $tarefa->idusuario_gut = Auth::id();
        $tarefa->save();

        return response()->json($tarefa);
    }
}
