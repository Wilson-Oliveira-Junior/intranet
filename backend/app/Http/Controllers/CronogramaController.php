<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Tarefa;
use App\Cliente;
use App\Cronograma;
use App\Services\Cronograma as ServicesCronograma;
use App\Services\Tarefa as ServicesTarefa;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;

class CronogramaController extends Controller{

    public function index(){

        $usuarios = User::Where('users.ativo', '=', '1')
            ->leftJoin('setor_usuarios', 'users.setor', '=', 'setor_usuarios.id')
            ->select(
                'users.id as id_usuario',
                'users.name as nome_usuario',
                'users.image as imagem_usuario',
                'users.email as email_usuario',
                'setor_usuarios.nome as nome_setor_usuario',
                DB::raw('(CASE
                WHEN users.setor = "1" THEN "botao-dev"
                WHEN users.setor = "2" THEN "botao-atendimento"
                WHEN users.setor = "3" THEN "botao-criacao"
                WHEN users.setor = "4" THEN "botao-comercial"
                WHEN users.setor = "5" THEN "botao-marketing"
                WHEN users.setor = "7" THEN "botao-adm"
                ELSE ""
                END) AS classes'),
                'setor_usuarios.id as id_setor_usuario'
            )
            ->orderBy('setor_usuarios.id')
            ->orderBy('users.id')
        ->get();

        return view('backend.cronograma.index',compact('usuarios'));
    }

    public function cronograma($id, ServicesCronograma $cronograma){

        $usuario = User::find($id);
        $usuarioSetor = $usuario->setor;

        $arrCronograma = $cronograma->fnMontaCronograma($id);
        //if(Auth::id() == 3){
         //   dd($arrCronograma);
        //}

        return view('backend.cronograma.usuario',compact(
            'arrCronograma','usuario', 'id'));
    }

    public function concluirTarefa(int $idtarefa, ServicesTarefa $t, ServicesCronograma $c){
        $tarefa = $t->concluirTarefa($idtarefa, $c);

        return response()->json($tarefa);
    }

    public function montaCronograma($idsetor, ServicesCronograma $cronograma){

        //dd($idsetor);
        $arrCronogramaEquipe= $cronograma->fnMontaCronogramaEquipe($idsetor);
        //if(Auth::id() == 3){
        //    dd($arrCronogramaEquipe);
        //}

        //dd($arrCronograma);
        return view('backend.cronograma..listagem.setores.setor',compact('arrCronogramaEquipe', 'idsetor'));
    }

    public function tarefas($id){

        $setor_usuario = User::Where('id',$id)->value('setor');
        $hoje               = (new Carbon())->format('Y-m-d');

        //dd($setor_usuario);
		//DB::enableQueryLog();
        $tarefas = Tarefa::Where(function ($queryuser) use ($setor_usuario,$id){
                $queryuser->where('id_equipe', $setor_usuario)
                ->orWhere('tbTarefas.id_responsavel', '=', $id);
        	})->where('tbTarefas.status', '=', 'Producao')
            ->Orwhere(function ($query) use ($hoje,$id){
                $query->where('tbTarefas.status', '=', 'Finalizado')
                ->where('tbTarefas.data_fim', '=', $hoje)
                ->where('tbTarefas.id_responsavel', '=', $id);
        	})
            // Puxando os usuários
            ->leftJoin('users', 'tbTarefas.id_responsavel',     '=', 'users.id')
            ->select(
                'tbTarefas.id as id_tarefa',
                'tbTarefas.id_responsavel',
                'tbTarefas.titulo as titulo',
                'tbTarefas.status as status',
                'tbTarefas.data_fim',
                'users.name as nome_responsavel',
                'users.email as email_responsavel',
                DB::raw('(CASE
                WHEN tbTarefas.id_responsavel is null THEN "backlog"
                WHEN tbTarefas.id_responsavel = "0" THEN "backlog"
                ELSE users.name
                END) AS descricao')
            )
            ->orderBy('tbTarefas.id_responsavel', 'ASC')
				->orderBy('tbTarefas.id', 'DESC')
        ->get();
		//	dd(DB::getQueryLog());
        //dd($tarefas);

        header('Content-Type: text/html; charset=utf-8');
        echo json_encode($tarefas);

    }

    public function adicionar($id_tarefa, $valor_data, $id_responsavel){

        if( Gate::denies('editar_cronograma') ){
            Session::flash('flash_mensagem', ['msg'=>'Sem permissão para editar cronograma.', 'class'=>'']);
            return redirect()->back();
        }

        $count = DB::table('tb_cronogramas')->where('tb_cronogramas.id_tarefa', '=', $id_tarefa)->where('tb_cronogramas.data', '=', $valor_data)->count();
        //dd($count);
        if($count >= 1){
            //Session::flash('flash_mensagem', ['msg'=>'Essa tarefa já foi atribuída neste dia !!!', 'class'=>'']);
            return response()->json(['message' => 'Essa tarefa já foi atribuída neste dia']);
        }else{

            // Verificando se a tarefa já está atrelado ao usuários
            $tes = DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)->where('id_responsavel', '=', NULL)->count();
            $idstatus  = Tarefa::where('id', '=', $id_tarefa)->value('id_status');
            if($idstatus == 680586 || $idstatus == 150042){ $idstatus = 792971;}
            if($tes == 1){
                DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                    ->update([
                        'id_responsavel' => $id_responsavel,
                        'id_equipe'      => NULL,
                        'id_status' => $idstatus
                ]);
            }else{
                DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                    ->update([
                        'id_status' => $idstatus
                ]);
            }

            $cronograma = new Cronograma();
            $cronograma->id_tarefa = $id_tarefa;
            $cronograma->data = $valor_data;
            $cronograma->save();

            //DB::table('tb_cronogramas')->insert([
            //    ['id_tarefa' => $id_tarefa, 'data' => $valor_data]
            //]);

            return response()->json($cronograma);
        }
    }

    public function remover($id_tarefa_cronograma){
        $id_tarefa = DB::table('tb_cronogramas')->where('tb_cronogramas.id_cronograma', '=', $id_tarefa_cronograma)->value('id_tarefa');

        DB::table('tb_cronogramas')->where('tb_cronogramas.id_cronograma', '=', $id_tarefa_cronograma)->delete();

        $vTarefaExisteCronograma = DB::table('tb_cronogramas')->where('tb_cronogramas.id_tarefa', '=', $id_tarefa)->count();

        if($vTarefaExisteCronograma == 0){
            //dd($id_tarefa);
            $up = DB::table('tbTarefas')->where('tbTarefas.id', $id_tarefa)
                    ->update([
                        'id_status' => '150042'
                ]);
            //    dd($up);
        }

        //Session::flash('flash_mensagem', ['msg'=>'Tarefa retirada do cronograma com sucesso !!!', 'class'=>'']);

        return response()->json("Tarefa removida do cronograma com sucesso.");
    }

    public function cronogramaUsuarios(){

        $usuarios = User::Where('users.ativo', '=', '1')
            ->select(
                DB::raw('(CASE
                WHEN users.setor = "1" THEN "cro-desenvolvimento"
                WHEN users.setor = "2" THEN "cro-atendimento"
                WHEN users.setor = "3" THEN "cro-criacao"
                WHEN users.setor = "4" THEN "cro-comercial"
                WHEN users.setor = "5" THEN "cro-marketing"
                WHEN users.setor = "7" THEN "cro-adm"
                ELSE ""
                END) AS classes'),
                'users.id',
                'users.name',
                'users.sobrenome',
                'users.setor'
            )
            ->orderBy('users.setor')
            ->get();

        return view('backend.cronograma.listagem.index',compact('usuarios'));
    }

}
