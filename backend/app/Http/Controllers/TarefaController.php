<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notifiable;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\DB;
use App\Notifications\NovaTarefaMail;
use App\Notifications\NovoSeguidorTarefa;
use App\Notifications\NovoComentarioMail;
use App\Notifications\EntregueTarefaMail;
use Gate;
use App\Tarefa;
use App\Lembrete;
use App\Cliente;
use App\SetorUsuario;
use App\User;
use App\Projeto;
use App\SeguidorTarefa;
use App\ComentarioProjeto;
use App\TiposTarefa;
use App\Anexo_Tarefa;
use App\Status;
use App\Comentario_Tarefa;
use App\Cronograma;
use App\Events\NovaTarefa;
use App\Events\NovoAnexo;
use App\Events\NovoComentarioTarefa;
use App\Services\Cronograma as ServicesCronograma;
use App\Services\Seguidor;
use App\Services\Tarefa as ServicesTarefa;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\File;
use ZipArchive;


class TarefaController extends Controller{

    use Notifiable;

    public function busca(Request $request){
        $dados = $request->all();
        //dd($dados);

        //$busca = $_POST['busca-tarefa'];
        $busca = Input::get ( 'busca-tarefa' );
        //print $busca;

        $tarefas = Tarefa::Where([['tbTarefas.titulo', 'LIKE', '%' . $busca . '%'],])
        ->orWhere('tb_projetos.projeto', 'LIKE', '%' . $busca . '%')
        ->orWhere('tbTarefas.id', '=',  $busca )
        ->orWhere('clientes.nome', '=',  $busca )

        // Puxando os usuários
        ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

        // Puxando os tipos de tarefas
        ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

        // Buscando dados do cliente
        ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

        // Buscando dados do cliente
        ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

        //
        ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

        ->select(
            'tbTarefas.id as id_tarefa',
            'tbTarefas.titulo as titulo',
            'tbTarefas.created_at as datacriada',
            'tbTarefas.tempo_trabalhado',
            'tbTarefas.data_desejada',

            'users.name as nome_responsavel',
            'users.email as email_responsavel',

            'tb_tipostarefas.nome as nome_tipo',
            'tb_tipostarefas.estimativa as estimativa_tipo',

            'tb_status.nome as nome_status',

            'clientes.nome as nome_cliente'
        )
        ->orderBy('tbTarefas.id', 'DESC')
        ->paginate(20);

        $tarefas->appends ( array (
            'busca-tarefa' => Input::get ( 'busca-tarefa' )
                ) );


        return view('backend.tarefa.para_mim.index',compact('tarefas'))->withQuery($busca);
    }

    /* Aba das " Para mim " */
		public function index($filtro = null){
	            //dd($filtro);
	            //date_default_timezone_set('America/Sao_Paulo');
	            $blFiltro = (!is_null($filtro))?$filtro:false;
	            // ID Setor do usuário logado
	            $usuario_id = auth()->user()->id;
	            //DB::connection()->enableQueryLog();
	            $tarefas = Tarefa::Where('id_responsavel', $usuario_id)
	                    ->where('tbTarefas.status', '=', 'Producao')
	                    ->when($blFiltro, function ($tarefas, $filtro) {
	                        return $tarefas->where('tbTarefas.id_status', $filtro);
	                    })
	                    // Puxando os usuários
	                    ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

	                    // Puxando os tipos de tarefas
	                    ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

	                    // Buscando dados do cliente
	                    ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

	                    // Buscando dados do cliente
	                    ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

	                    //
	                    ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

	                    ->select(
	                        'tbTarefas.id as id_tarefa',
	                        'tbTarefas.titulo as titulo',
	                        'tbTarefas.created_at as datacriada',
	                        'tbTarefas.tempo_trabalhado',
	                        'tbTarefas.data_desejada',

	                        'users.name as nome_responsavel',
	                        'users.email as email_responsavel',

	                        'tb_tipostarefas.nome as nome_tipo',
	                        'tb_tipostarefas.estimativa as estimativa_tipo',

	                        'tb_status.id as id_status_tarefa',
	                        'tb_status.nome as nome_status',

	                        'clientes.nome as nome_cliente'
	                    )
						->orderBy('tbTarefas.tarefa_ordem', 'DESC')
						->orderBy('tbTarefas.id', 'ASC')
	                    ->paginate(30);
	                    //$queries = DB::getQueryLog();
	            //dd($tarefas);
	            $qryStatus = Tarefa::Where('id_responsavel', $usuario_id)
	                    ->where('tbTarefas.status', '=', 'Producao')
	                    // Puxando os usuários
	                    ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

	                    // Puxando os tipos de tarefas
	                    ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

	                    // Buscando dados do cliente
	                    ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

	                    // Buscando dados do cliente
	                    ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

	                    //
	                    ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

	                    ->select(
	                        'tbTarefas.id as id_tarefa',
	                        'tbTarefas.titulo as titulo',
	                        'tbTarefas.created_at as datacriada',
	                        'tbTarefas.tempo_trabalhado',
	                        'tbTarefas.data_desejada',

	                        'users.name as nome_responsavel',
	                        'users.email as email_responsavel',

	                        'tb_tipostarefas.nome as nome_tipo',
	                        'tb_tipostarefas.estimativa as estimativa_tipo',

	                        'tb_status.id as id_status_tarefa',
	                        'tb_status.nome as nome_status',

	                        'clientes.nome as nome_cliente'
	                    )->get();
	            $status = $qryStatus->pluck('id_status_tarefa', 'nome_status');

	            $arrStatus = $status->all();
	            //dd($arrStatus);

	            return view('backend.tarefa.para_mim.index',
	            compact('tarefas', 'arrStatus', 'filtro'));
	        }

        // Teste
        public function index2(){
            date_default_timezone_set('America/Sao_Paulo');

            // ID Setor do usuário logado
            $usuario_id = auth()->user()->id;

            $tarefas = Tarefa::Where('id_responsavel', $usuario_id)
                    ->where('tbTarefas.status', '=', 'Producao')

                    // Puxando os usuários
                    ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

                    // Puxando os tipos de tarefas
                    ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

                    // Buscando dados do cliente
                    ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

                    // Buscando dados do cliente
                    ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

                    //
                    ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

                    ->select(
                        'tbTarefas.id as id_tarefa',
                        'tbTarefas.titulo as titulo',
                        'tbTarefas.created_at as datacriada',
                        'tbTarefas.tempo_trabalhado',
                        'tbTarefas.data_desejada',
                        'tbTarefas.tarefa_ordem',

                        'users.name as nome_responsavel',
                        'users.email as email_responsavel',

                        'tb_tipostarefas.nome as nome_tipo',
                        'tb_tipostarefas.estimativa as estimativa_tipo',

                        'tb_status.id as id_status_tarefa',
                        'tb_status.nome as nome_status',

                        'clientes.nome as nome_cliente'
                    )
                    ->orderBy('tbTarefas.tarefa_ordem', 'ASC')
                    ->paginate(20);
            //dd($tarefas);

            return view('backend.tarefa.para_mim.index2',
            compact('tarefas'));
        }

        public function entregues(){
            date_default_timezone_set('America/Sao_Paulo');

            // Números
                $lembretes_entregue     = self::LembreteEntregue();
                $quantidade_clientes    = self::QuantidadeClientes();
                $quantidade_usuarios    = self::QuantidadeUsuarios();
                $quantidade_lembrete    = self::QuantidadeLembrete();

            // ID Setor do usuário logado
                $usuario_id = auth()->user()->id;

            $tarefas = Tarefa::Where('id_responsavel', $usuario_id)
                    ->where('tbTarefas.status', '=', 'Finalizado')
                    ->leftJoin('users',             'tbTarefas.id_responsavel',    '=', 'users.id')
                    ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',           '=', 'tb_tipostarefas.id')

                    // Buscando dados do cliente
                    ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',        '=', 'tb_projetos.id')

                    // Buscando dados do cliente
                    ->leftJoin('clientes',              'tb_projetos.cliente_id',        '=', 'clientes.cliente_id')

                    ->select(
                        'tbTarefas.id as id_tarefa',
                        'tbTarefas.titulo as titulo',
                        'tbTarefas.created_at as datacriada',
                        'tbTarefas.data_fim as dataentregue',
                        'tbTarefas.data_desejada',
                        'tbTarefas.tempo_trabalhado',

                        'users.name as nome_responsavel',
                        'users.email as email_responsavel',

                        'tb_tipostarefas.nome as nome_tipo',
                        'tb_tipostarefas.estimativa as estimativa_tipo',

                        'clientes.nome as nome_cliente'
                    )
                    ->orderBy('tbTarefas.data_fim', 'DESC')
                ->paginate(20);

            //dd($tarefas);

            return view('backend.tarefa.para_mim.entregue',
            compact('tarefas', 'lembretes_entregue', 'quantidade_clientes', 'quantidade_usuarios', 'quantidade_lembrete', 'comentarios'));
        }
    /* Fim das Aba das " Para mim " */

    /* Aba das "Que eu Criei " */
        public function criei(){
            date_default_timezone_set('America/Sao_Paulo');



            // ID Setor do usuário logado
                $usuario_id = auth()->user()->id;

            $tarefas = Tarefa::Where('id_criado_por', $usuario_id)
                    ->where('tbTarefas.status', '=', 'Producao')

                    // Puxando os usuários
                    ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

                    // Puxando os tipos de tarefas
                    ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

                    // Buscando dados do cliente
                    ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

                    // Buscando dados do cliente
                    ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

                    //
                    ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

                    ->select(
                        'tbTarefas.id as id_tarefa',
                        'tbTarefas.titulo as titulo',
                        'tbTarefas.created_at as datacriada',
                        'tbTarefas.data_desejada',

                        'users.name as id_responsavel',
                        'users.name as nome_responsavel',
                        'users.email as email_responsavel',

                        'tb_tipostarefas.nome as nome_tipo',
                        'tb_tipostarefas.estimativa as estimativa_tipo',

                        'tb_status.id as id_status_tarefa',
                        'tb_status.nome as nome_status',

                        'clientes.nome as nome_cliente'
                    )
                    ->orderBy('tbTarefas.id', 'DESC')
                    ->paginate(20);

            //dd($tarefas);

            return view('backend.tarefa.criadas.index',
            compact('tarefas', 'comentarios'));
        }

        public function criei_entregues(){
            date_default_timezone_set('America/Sao_Paulo');


            // ID Setor do usuário logado
                $usuario_id = auth()->user()->id;

            $tarefas = Tarefa::Where('id_criado_por', $usuario_id)
                    ->where('tbTarefas.status', '=', 'Finalizado')

                    // Puxando os usuários
                    ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

                    // Puxando os tipos de tarefas
                    ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

                    // Buscando dados do cliente
                    ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

                    // Buscando dados do cliente
                    ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

                    //
                    ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

                    ->select(
                        'tbTarefas.id as id_tarefa',
                        'tbTarefas.titulo as titulo',
                        'tbTarefas.created_at as datacriada',
                        'tbTarefas.data_fim as dataentregue',
                        'tbTarefas.data_desejada',
                        'tbTarefas.tempo_trabalhado as totaltrabalhado',

                        'users.name as id_responsavel',
                        'users.name as nome_responsavel',
                        'users.email as email_responsavel',

                        'tb_tipostarefas.nome as nome_tipo',
                        'tb_tipostarefas.estimativa as estimativa_tipo',

                        'clientes.nome as nome_cliente'
                    )
                    ->orderBy('tbTarefas.data_fim', 'DESC')
                    ->paginate(20);

            //dd($tarefas);

            return view('backend.tarefa.criadas.index-entregues',
            compact('tarefas', 'comentarios'));
        }
    /* Fim da Aba das "Que eu Criei " */

    /* Aba das " Que eu Sigo " */
        public function seguindo(){
            date_default_timezone_set('America/Sao_Paulo');

            // ID Setor do usuário logado
                $usuario_id = auth()->user()->id;

            $tarefas = SeguidorTarefa::Where('id_usuario_seguidor', $usuario_id)
                        ->leftJoin('tbTarefas',         'tb_seguidores_tarefas.id_tarefa', '=', 'tbTarefas.id')
                        ->where('tbTarefas.status', 'Producao')
                        ->leftJoin('users',             'tbTarefas.id_responsavel',    '=', 'users.id')
                        ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',           '=', 'tb_tipostarefas.id')
                        ->leftJoin('clientes',          'tbTarefas.id_projeto',        '=', 'clientes.cliente_id')
                        ->leftJoin('tb_status',         'tbTarefas.id_status',         '=', 'tb_status.id')
                        ->select(
                            'tbTarefas.id as id_tarefas',
                            'tbTarefas.titulo as titulo',
                            'tbTarefas.created_at as datacriada',
                            'tbTarefas.tempo_trabalhado',
                            'tbTarefas.data_desejada',

                            'users.name as id_responsavel',
                            'users.name as nome_responsavel',
                            'users.email as email_responsavel',

                            'tb_tipostarefas.nome as nome_tipo',
                            'tb_tipostarefas.estimativa as estimativa_tipo',

                            'tb_status.nome as nome_status',

                            'clientes.nome as nome_cliente'
                        )
                ->paginate(20);


            $tarefas_contagens = SeguidorTarefa::Where('id_usuario_seguidor', $usuario_id)
                ->leftJoin('tbTarefas',         'tb_seguidores_tarefas.id_tarefa', '=', 'tbTarefas.id')
                ->where('tbTarefas.status', 'Producao')
                ->leftJoin('users',             'tbTarefas.id_responsavel',    '=', 'users.id')
                ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',           '=', 'tb_tipostarefas.id')
                ->leftJoin('clientes',          'tbTarefas.id_projeto',        '=', 'clientes.cliente_id')
                ->leftJoin('tb_status',         'tbTarefas.id_status',         '=', 'tb_status.id')
                ->select(
                    'tbTarefas.id as id_tarefas',
                    'tbTarefas.titulo as titulo',
                    'tbTarefas.created_at as datacriada',
                    'tbTarefas.data_desejada',
                    'users.name as id_responsavel',
                    'users.name as nome_responsavel',
                    'users.email as email_responsavel',

                    'tb_tipostarefas.nome as nome_tipo',
                    'tb_tipostarefas.estimativa as estimativa_tipo',

                    'tb_status.nome as nome_status',

                    'clientes.nome as nome_cliente'
                )
            ->count();

            //dd($tarefas_contagens);

            return view('backend.tarefa.seguindo.index',
            compact('tarefas', 'tarefas_contagens', 'comentarios'));
        }

        /* Aba das " Backlog " */
        public function backlog($idequipe){

            if($idequipe <> ""){
                $vEquipe = $idequipe;
            }else{
                $vEquipe = auth()->user()->setor;
            }

            //dd($vEquipe);
                $setores = SetorUsuario::where('setor_usuarios.id', $vEquipe)->select('id','nome')->get();
            //dd($setores);

            $tarefas = Tarefa::whereNotNull('id_equipe')
                        ->where('tbTarefas.status', 'Producao')
                        ->where('tbTarefas.id_equipe', $vEquipe)
                        // Puxando os usuários
                        ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

                        // Puxando os tipos de tarefas
                        ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

                        // Buscando dados do cliente
                        ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

                        // Buscando dados do cliente
                        ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

                        //
                        ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

                        ->select(
                            'tbTarefas.id as id_tarefas',
                            'tbTarefas.titulo as titulo',
                            'tbTarefas.data_criado as datacriada',
                            'tbTarefas.data_desejada',

                            'users.name as id_responsavel',
                            'users.name as nome_responsavel',
                            'users.email as email_responsavel',

                            'tb_tipostarefas.nome as nome_tipo',
                            'tb_tipostarefas.estimativa as estimativa_tipo',

                            'tb_status.nome as nome_status',

                            'clientes.nome as nome_cliente'
                        )
                    ->orderBy('tbTarefas.data_criado', 'DESC')
                ->paginate(40);
            //dd($tarefas);

            $usuarios = User::Where('users.ativo', '=', '1')->where('setor', '=', $idequipe)->get();
            //dd($usuarios);

            $permissao = DB::table('role_user')->where('user_id', '=', auth()->user()->id)->where('role_id', '=', '2')->count();
            if(DB::table('role_user')->where('user_id', '=', auth()->user()->id)->where('role_id', '8')->count() >0){
                $permissao++;
            }
            if(DB::table('role_user')->where('user_id', '=', auth()->user()->id)->where('role_id', '7')->count() >0){
                $permissao++;
            }
            $permissaoadm   = DB::table('role_user')->where('user_id', '=', auth()->user()->id)->where('role_id', '=', '1')->count();

            //dd($permissao);

            return view('backend.tarefa.backlog.index',compact('permissao', 'usuarios', 'tarefas', 'setores', 'vEquipe', 'permissaoadm'));
        }

        public function seguindo_entregues(){
            date_default_timezone_set('America/Sao_Paulo');

            // Números
                $lembretes_entregue     = self::LembreteEntregue();
                $quantidade_clientes    = self::QuantidadeClientes();
                $quantidade_usuarios    = self::QuantidadeUsuarios();
                $quantidade_lembrete    = self::QuantidadeLembrete();

            // ID Setor do usuário logado
                $usuario_id = auth()->user()->id;

            $tarefas = SeguidorTarefa::Where('id_usuario_seguidor', $usuario_id)
                        ->leftJoin('tbTarefas',         'tb_seguidores_tarefas.id_tarefa', '=', 'tbTarefas.id')
                        ->where('tbTarefas.status', 'Finalizado')
                        ->leftJoin('users',             'tbTarefas.id_responsavel',    '=', 'users.id')
                        ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',           '=', 'tb_tipostarefas.id')
                        ->leftJoin('clientes',          'tbTarefas.id_projeto',        '=', 'clientes.cliente_id')
                        ->leftJoin('tb_status',         'tbTarefas.id_status',         '=', 'tb_status.id')
                        ->select(
                            'tbTarefas.id as id_tarefas',
                            'tbTarefas.titulo as titulo',
                            'tbTarefas.created_at as datacriada',
                            'tbTarefas.data_desejada',

                            'users.name as id_responsavel',
                            'users.name as nome_responsavel',
                            'users.email as email_responsavel',

                            'tb_tipostarefas.nome as nome_tipo',
                            'tb_tipostarefas.estimativa as estimativa_tipo',

                            'tb_status.nome as nome_status',

                            'clientes.nome as nome_cliente'
                        )
                ->paginate(20);


            $tarefas_contagens = SeguidorTarefa::Where('id_usuario_seguidor', $usuario_id)
                ->leftJoin('tbTarefas',         'tb_seguidores_tarefas.id_tarefa', '=', 'tbTarefas.id')
                ->where('tbTarefas.status', 'Finalizado')
                ->leftJoin('users',             'tbTarefas.id_responsavel',    '=', 'users.id')
                ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',           '=', 'tb_tipostarefas.id')
                ->leftJoin('clientes',          'tbTarefas.id_projeto',        '=', 'clientes.cliente_id')
                ->leftJoin('tb_status',         'tbTarefas.id_status',         '=', 'tb_status.id')
                ->select(
                    'tbTarefas.id as id_tarefas',
                    'tbTarefas.titulo as titulo',
                    'tbTarefas.created_at as datacriada',
                    'tbTarefas.data_desejada',

                    'users.name as id_responsavel',
                    'users.name as nome_responsavel',
                    'users.email as email_responsavel',

                    'tb_tipostarefas.nome as nome_tipo',
                    'tb_tipostarefas.estimativa as estimativa_tipo',

                    'tb_status.nome as nome_status',

                    'clientes.nome as nome_cliente'
                )
            ->count();

            //dd($tarefas);

            return view('backend.tarefa.seguindo.index-entregue',
            compact('tarefas', 'tarefas_contagens', 'lembretes_entregue', 'quantidade_clientes', 'quantidade_usuarios', 'quantidade_lembrete', 'comentarios'));
        }
    /* Fim daAba das " Que eu Sigo " */

    public function editar($id){
        date_default_timezone_set('America/Sao_Paulo');

        // Tarefas
            $tarefas = Tarefa::Where('tbTarefas.id', $id)
                // Buscando dados do usuário responsavel
                ->leftJoin('users',                     'tbTarefas.id_responsavel',     '=', 'users.id')

                // Buscando dados do usuário criador
                ->leftJoin('users as tb_usuarios',      'tbTarefas.id_criado_por',      '=', 'tb_usuarios.id')

                // Buscando os dados do setor do usuário responsavel
                ->leftJoin('setor_usuarios',      'users.setor',      '=', 'setor_usuarios.id')

                // Buscando os tipos de tarefas
                ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',           '=', 'tb_tipostarefas.id')

                // Buscando dados do cliente
                ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',        '=', 'tb_projetos.id')

                // Buscando dados do cliente
                ->leftJoin('clientes',              'tb_projetos.cliente_id',        '=', 'clientes.cliente_id')

                // Buscando os dados de Status
                ->leftJoin('tb_status',         'tbTarefas.id_status',         '=', 'tb_status.id')
                ->select(
                    'tbTarefas.id as id_tarefa',
                    'tbTarefas.titulo as titulo',
                    'tbTarefas.descricao as descricao',
                    'tbTarefas.data_criado as datacriada',
                    'tbTarefas.tempo_trabalhado',
                    'tbTarefas.data_fim',
                    'tbTarefas.id_criado_por as id_quem_criou',
                    'tbTarefas.data_desejada',
                    'tbTarefas.created_at',

                    'users.setor as id_setor_responsavel',
                    'users.name as nome_responsavel',
                    'users.email as email_responsavel',
                    'users.image as imagem_responsavel',

                    'tb_usuarios.id as id_usuario_criador',
                    'tb_usuarios.name as nome_criador',
                    'tb_usuarios.sobrenome as sobrenome_criador',
                    'tb_usuarios.email as email_criador',

                    'setor_usuarios.nome as nome_setor',
                    'setor_usuarios.email as email_setor',

                    'tb_tipostarefas.nome as nome_tipo',
                    'tb_tipostarefas.estimativa as estimativa_tipo',

                    'tb_status.id as id_status',
                    'tb_status.nome as nome_status',
                    'tb_status.descricao as descricao_status',

                    'clientes.nome as nome_cliente',
                    'tb_projetos.projeto as tipo_projeto'
                )
                ->orderBy('tbTarefas.id', 'ASC')
                ->get();
        //dd($tarefas);

        // Comentários
            $comentarios = Comentario_Tarefa::Where('tb_comentarios_tarefas.id_tarefa', '=', $id)
                ->where('excluido', 0)
                ->leftJoin('users', 'tb_comentarios_tarefas.id_usuario', '=', 'users.id')
                ->select(
                        'tb_comentarios_tarefas.id as id_comentario',
                        'tb_comentarios_tarefas.id_usuario as id_usuario_comentario',
                        'tb_comentarios_tarefas.id_tarefa as id_tarefa_comentario',
                        'tb_comentarios_tarefas.comentario',
                        'tb_comentarios_tarefas.created_at',
                        'users.name',
                        'users.sobrenome',
                        'users.image'
                )
                ->orderBy('tb_comentarios_tarefas.id', 'DESC')
                ->get();

            $contador_comentarios = Comentario_Tarefa::Where('tb_comentarios_tarefas.id_tarefa', '=', $id)
                    ->where('excluido', 0)
                    ->count();
            //dd($contador_comentarios);
        //dd($comentarios);

        // Anexos

            $anexos = DB::table('tb_anexos_tarefas')->where('tb_anexos_tarefas.id_tarefa', '=', $id)
                ->leftJoin('users', 'tb_anexos_tarefas.id_usuario_postou', '=', 'users.id')
                ->select(
                    'tb_anexos_tarefas.id as id_anexo',
                    'tb_anexos_tarefas.id_usuario_postou',
                    'tb_anexos_tarefas.anexo',
                    'tb_anexos_tarefas.nome_arquivo',
                    'tb_anexos_tarefas.tipo_arquivo',
                    'tb_anexos_tarefas.created_at',
                    'users.name',
                    'users.sobrenome',
                    'users.image'
                )->orderBy('tb_anexos_tarefas.created_at', 'DESC')
                ->orderBy('tb_anexos_tarefas.id', 'DESC')
                ->get();
            //dd($anexos)

            $contador_anexos = DB::table('tb_anexos_tarefas')->where('tb_anexos_tarefas.id_tarefa', '=', $id)->count();
            //dd($contador_anexos);

        // Fim Anexos

        // Seguidores

            $seguidores = DB::table('tb_seguidores_tarefas')->where('tb_seguidores_tarefas.id_tarefa', '=', $id)
                ->leftJoin('users', 'tb_seguidores_tarefas.id_usuario_seguidor', '=', 'users.id')
                ->select(
                    'tb_seguidores_tarefas.id',
                    'tb_seguidores_tarefas.id_usuario_postou',
                    'tb_seguidores_tarefas.id_usuario_seguidor',
                    'tb_seguidores_tarefas.id_tarefa',
                    'users.name',
                    'users.image'
                )
                ->orderBy('tb_seguidores_tarefas.id', 'DESC')
            ->get();

            $contador_seguidores = DB::table('tb_seguidores_tarefas')->where('tb_seguidores_tarefas.id_tarefa', '=', $id)->count();
            //dd($contador_anexos);

        // Fim Seguidores

        // Configurando Permissão de visualização

        // Fim da Permissão

        return view('backend.tarefa.editar.index',
            compact('contador_seguidores', 'contador_anexos' ,'contador_comentarios' ,'tarefas' , 'seguidores', 'anexos' , 'comentarios'));
    }

    public function salvar(Request $request, Seguidor $seguidor){

        // Recuperando os dados do formulário
            $dados = $request->all();
            //dd($dados);

        // Avisando que vai ser criado uma nova tarefa
            $tarefas = new Tarefa();
        // Fim

        /* Jogando dados nas colunas no bancos de dados */
            $tarefas->titulo            = $dados['titulo'];
            $tarefas->descricao         = $dados['descricao'];
            $tarefas->inicio_tarefa     = NULL;


        // Montando a condição do usuário
            if( isset( $dados['idusuario'] ) ){
                $tarefas->id_responsavel    = $dados['idusuario'];
                $tarefas->email             = User::Where('id', $dados['idusuario'])->value('email');
                $tarefas->id_equipe         = NULL;
                $tarefas->id_status         = 150042;
                $email = User::Where('id', $dados['idusuario'])->value('email');
            }else{
                $tarefas->id_responsavel    = NULL;
                $tarefas->id_equipe         = $dados['setorestarefa'];
                $tarefas->email             = SetorUsuario::Where('id', $dados['setorestarefa'])->value('email');
                $tarefas->id_status         = 680586;
                $email = SetorUsuario::Where('id', $dados['setorestarefa'])->value('email');
            }

        /* Jogando dados nas colunas no bancos de dados */
            $tarefas->id_tipo           = $dados['tipotarefa'];
            $tarefas->id_projeto        = $dados['projetotarefa'];
            $tarefas->id_criado_por     = $dados['criado_por'];
            $tarefas->data_desejada     = $dados['datadesejada-tarefa'];
            $tarefas->data_inicio       = date( 'Y-m-d' );
            $tarefas->data_fim          = NULL;
            $tarefas->data_criado       = date( 'Y-m-d' );
            $tarefas->tempo_trabalhado  = "0";
            $tarefas->status            = "Producao";

        $tarefas->save();

            if($dados['tipotarefa'] == 940376){
                $comentario                 = new ComentarioProjeto();
                $comentario->id_usuario     = 0;
                $comentario->id_projeto     = $dados['projetotarefa'];
                $comentario->comentario     = 'Tarefa criada para a criação desenvolver o layout';
                $comentario->tipo           = 'S';
                $comentario->save();
            }

            if($dados['tipotarefa'] == 188913 || $dados['tipotarefa'] == 643364 || $dados['tipotarefa'] == 1100378){
                $comentario                 = new ComentarioProjeto();
                $comentario->id_usuario     = 0;
                $comentario->id_projeto     = $dados['projetotarefa'];
                $comentario->comentario     = 'Tarefa criada para o desenvolvimento';
                $comentario->tipo           = 'S';
                $comentario->save();
            }

        /* Configurando Multiplos Anexos */


            if( $request->anexos != null ){
                foreach ($request->anexos as $file) {
                    //dd($file->getClientOriginalName());
                    // Criando um número randomico
                    $rand = rand(11111,99999);

                    // Criando o diretório que vai ficar o arquivo
                    $diretorio = "anexos/".date( 'm' )."/";

                    // Recuperando a extensão do arquivo
                    $ext = $file->guessClientExtension();
                    //$nomesemextensao = substr($file->getClientOriginalName(),0,-4);
                    $nomesemextensao = substr($file->getClientOriginalName(),0,-(strlen($ext)+1));
                    //dd($nomesemextensao);
                    // Definindo o nome do arquivo
                    $nomeArquivo = $nomesemextensao . "_anexo_".$rand.".".$ext;

                    // Movendo o anexo
                    $file->move($diretorio,$nomeArquivo);

                    // Colocando os dados no banco de dados
                    DB::table('tb_anexos_tarefas')->insert(
                        [
                            'id_usuario_postou' 	    => 	$dados['criado_por'],
                            'id_tarefa' 				=>  $tarefas->id,
                            'anexo' 					=>  $diretorio.''.$nomeArquivo,
                            'nome_arquivo'              =>  $nomesemextensao,
                            'tipo_arquivo'              =>  $ext
                        ]
                    );
                }
            }
        /* Fim da Configurando Multiplos Anexos */

        /* Configurando o Seguidor */
        if(isset($dados['idseguidor'])){
            $resultado = $seguidor->adicionaSeguidor($tarefas, $dados['idseguidor']);
        }else{
            $resultado = $seguidor->adicionaSeguidor($tarefas, ["3","27","34"]);
        }
        $resultado = $seguidor->adicionaResponsaveis($tarefas);
        //dd($resultado);

    /* Fim das Configurações de Seguidores */
        /* Fim das Configurações de Seguidores */

        /*======= Enviando Notificação ao Destinário da Tarefa =======*/

        $aberta     = User::Where('id', $tarefas->id_criado_por)->value('name');
        $aberta_sobrenome     = User::Where('id', $tarefas->id_criado_por)->value('sobrenome');
        $responsavel     = User::Where('id', $tarefas->id_responsavel)->value('name');
        $responsavel_sobrenome     = User::Where('id', $tarefas->id_responsavel)->value('sobrenome');
        $setor = SetorUsuario::Where('id', $tarefas->id_equipe)->value('nome');
        $evento = new NovaTarefa($tarefas, $responsavel, $setor,
                    $responsavel_sobrenome, $aberta, $aberta_sobrenome, $email);
        event($evento);
            //$tarefas->notify(new NovaTarefaMail());


        /*======= Fim Notificações =======*/

        /*======= Atualizando o CAMPO EMAIL para o email de quem CRIOU a TAREFA =======*/
            DB::table('tbTarefas')->where('tbTarefas.id', '=', $tarefas->id)
                            ->update([
                                'email' => Auth::user()->email
                            ]);
        /*======= Fim do UPDATE =======*/

        return redirect()->route('backend.tarefa.editar', $tarefas->id);
    }

    public function concluir($id, ServicesTarefa $t, ServicesCronograma $c){

        $tarefa = $t->concluirTarefa($id, $c);

        return redirect()->back();
    }

    public function reabrir($id){

        DB::table('tbTarefas')->where('tbTarefas.id', '=', $id)
                        ->update([
                            'data_fim' => NULL,
                            'status' => 'Producao',
                            'id_status' => 792970
                        ]);

        return redirect()->route('backend.tarefa.editar', $id);
    }

    /* Funções Ajax */
        public function ajax($id_tarefa, $valor_tempo){

            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                            ->update([
                                'tempo_trabalhado' => $valor_tempo,
                            ]);

        }

        public function ajaxStatusTarefa($id_tarefa, $id_status, $id_usuario){

            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                ->update([
                    'id_status' => $id_status,
                ]);

            $nome_status  = Status::where('id', '=', $id_status)->value('nome');
            $nome_usuario = User::where('id', '=', $id_usuario)->value('name');
            $sobrenome    = User::where('id', '=', $id_usuario)->value('sobrenome');

            DB::table('tb_comentarios_tarefas')
                ->insert([
                    'id_usuario'    =>  '0',
                    'id_tarefa'     =>  $id_tarefa,
                    'comentario'    =>  'O usuário "'. $nome_usuario .' '. $sobrenome. '" alterou o status para "'. $nome_status .'"',
                    'created_at'    =>  date('Y-m-d H:i:s'),
                ]);
        }

        public function ajaxUsuarioTarefa($id_tarefa, $id_usuario, $id_postou){
            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                ->update([
                    'id_equipe'      => NULL,
                    'id_responsavel' => $id_usuario,
                    'id_status' => 150042
                ]);

            $nome_usuario   = User::where('id', '=', $id_usuario)->value('name');
            $sobre_usuario  = User::where('id', '=', $id_usuario)->value('sobrenome');

            $nome_usuario2 = User::where('id', '=', $id_postou)->value('name');
            $sobrenome    = User::where('id', '=', $id_postou)->value('sobrenome');


            DB::table('tb_comentarios_tarefas')
                    ->insert([
                        'id_usuario'    =>  '0',
                        'id_tarefa'     =>  $id_tarefa,
                        'comentario'    =>  'O usuário "'. $nome_usuario2 .' '. $sobrenome. '" transferiu a tarefa para o usuário "'. $nome_usuario .' '. $sobre_usuario .'"',
                        'created_at'    =>  date('Y-m-d H:i:s'),
                    ]);
        }

        public function ajaxAlterarTipo($id_tarefa, $id_tipo, $id_usuario){
            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                ->update([
                    'id_tipo' => $id_tipo,
                ]);

            $nome_tipo = TiposTarefa::where('id', '=', $id_tipo)->value('nome');
            $nome_usuario = User::where('id', '=', $id_usuario)->value('name');
            $sobrenome    = User::where('id', '=', $id_usuario)->value('sobrenome');

            DB::table('tb_comentarios_tarefas')
                ->insert([
                    'id_usuario'    =>  '0',
                    'id_tarefa'     =>  $id_tarefa,
                    'comentario'    =>  'O usuário "'. $nome_usuario .' '. $sobrenome. '" alterou o tipo para "'. $nome_tipo .'"',
                    'created_at'    =>  date('Y-m-d H:i:s'),
                ]);
        }

        public function ajaxAlterarProjeto($id_tarefa, $id_projeto, $id_usuario){
            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                ->update([
                    'id_projeto' => $id_projeto,
                ]);

            $nome_projeto = Projeto::where('id', '=', $id_projeto)->value('projeto');
            $nome_usuario = User::where('id', '=', $id_usuario)->value('name');
            $sobrenome    = User::where('id', '=', $id_usuario)->value('sobrenome');

            DB::table('tb_comentarios_tarefas')
                ->insert([
                    'id_usuario'    =>  '0',
                    'id_tarefa'     =>  $id_tarefa,
                    'comentario'    =>  'O usuário "'. $nome_usuario .' '. $sobrenome. '" alterou o projeto para "'. $nome_projeto .'"',
                    'created_at'    =>  date('Y-m-d H:i:s'),
                ]);
        }

        public function alterarTitulo(Request $request, $id_tarefa){

            $dados = $request->all();

            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                ->update([
                    'titulo' => $dados['titulo-tarefa'],
                ]);

            DB::table('tb_comentarios_tarefas')
                ->insert([
                    'id_usuario'    =>  '0',
                    'id_tarefa'     =>  $id_tarefa,
                    'comentario'    =>  'O usuário "'. $dados['nome_usuario_principal'] .'" alterou o título para "'. $dados['titulo-tarefa'] .'"',
                    'created_at'    =>  date('Y-m-d H:i:s'),
                ]);
        }

        public function alterarDescricao(Request $request, $id_tarefa){

            $dados = $request->all();
            //dd($dados);

            DB::table('tbTarefas')->where('tbTarefas.id', '=', $id_tarefa)
                ->update([
                    'descricao' => $dados['alteradescricao'],
                ]);


            DB::table('tb_comentarios_tarefas')
                ->insert([
                    'id_usuario'    =>  '0',
                    'id_tarefa'     =>  $id_tarefa,
                    'comentario'    =>  'O usuário "'. $dados['nome_decricao_principal'] .'" alterou a descrição da tarefa.',
                    'created_at'    =>  date('Y-m-d H:i:s'),
                ]);

        }
    /* Fim Funções Ajax */

    /*
    *   Título: Tarefas dos usuários
    *   Descrição: Funções para os usuários poderem ver as tarefas dos outros.
    */
        public function tarefaUser($idusuario){
            date_default_timezone_set('America/Sao_Paulo');



            $tarefas = Tarefa::Where('id_responsavel', $idusuario)
                ->where('tbTarefas.status', '=', 'Producao')

                // Puxando os usuários
                ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

                // Puxando os tipos de tarefas
                ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

                // Buscando dados do cliente
                ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

                // Buscando dados do cliente
                ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

                //
                ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

                ->select(
                    'tbTarefas.id as id_tarefa',
                    'tbTarefas.titulo as titulo',
                    'tbTarefas.created_at as datacriada',
                    'tbTarefas.tempo_trabalhado',
                    'tbTarefas.data_desejada',

                    'users.name as nome_responsavel',
                    'users.email as email_responsavel',

                    'tb_tipostarefas.nome as nome_tipo',
                    'tb_tipostarefas.estimativa as estimativa_tipo',

                    'tb_status.id as id_status_tarefa',
                    'tb_status.nome as nome_status',

                    'clientes.nome as nome_cliente'
                )
				->orderBy('tbTarefas.tarefa_ordem', 'DESC')
				->orderBy('tbTarefas.id', 'ASC')
                ->paginate(20);
            //dd($tarefas);

            $usuario = User::find($idusuario);
            //dd($usuario);

            if( Gate::denies('listar_tarefa_usuarios') ){
                \Session::flash('flash_mensagem', ['msg'=>'Sem permissão para listar as Tarefas do usuário', 'class'=>'']);
                return redirect()->back();
            }

            return view('backend.tarefa.usuario.index',
            compact('usuario','tarefas', 'comentarios'));

        }

        public function tarefaUserEntregues($idusuario){
            date_default_timezone_set('America/Sao_Paulo');

            // Números
                $lembretes_entregue     = self::LembreteEntregue();
                $quantidade_clientes    = self::QuantidadeClientes();
                $quantidade_usuarios    = self::QuantidadeUsuarios();
                $quantidade_lembrete    = self::QuantidadeLembrete();
            // Fim dos Números

            $tarefas = Tarefa::Where('id_responsavel', $idusuario)
                ->where('tbTarefas.status', '=', 'Finalizado')

                // Puxando os usuários
                ->leftJoin('users',             'tbTarefas.id_responsavel',     '=', 'users.id')

                // Puxando os tipos de tarefas
                ->leftJoin('tb_tipostarefas',   'tbTarefas.id_tipo',            '=', 'tb_tipostarefas.id')

                // Buscando dados do cliente
                ->leftJoin('tb_projetos',          'tbTarefas.id_projeto',      '=', 'tb_projetos.id')

                // Buscando dados do cliente
                ->leftJoin('clientes',              'tb_projetos.cliente_id',   '=', 'clientes.cliente_id')

                //
                ->leftJoin('tb_status',         'tbTarefas.id_status',          '=', 'tb_status.id')

                ->select(
                    'tbTarefas.id as id_tarefa',
                    'tbTarefas.titulo as titulo',
                    'tbTarefas.created_at as datacriada',
                    'tbTarefas.tempo_trabalhado',
                    'tbTarefas.data_desejada',

                    'users.name as nome_responsavel',
                    'users.email as email_responsavel',

                    'tb_tipostarefas.nome as nome_tipo',
                    'tb_tipostarefas.estimativa as estimativa_tipo',

                    'tb_status.nome as nome_status',

                    'clientes.nome as nome_cliente'
                )
                ->orderBy('tbTarefas.data_fim', 'DESC')
                ->paginate(20);
            //dd($tarefas);

            $usuario = User::find($idusuario);
            //dd($usuario);

            if( Gate::denies('listar_tarefa_usuarios') ){
                \Session::flash('flash_mensagem', ['msg'=>'Sem permissão para listar as Tarefas do usuário', 'class'=>'']);
                return redirect()->back();
            }

            return view('backend.tarefa.usuario.index-entregue',
            compact('usuario','tarefas', 'lembretes_entregue', 'quantidade_clientes', 'quantidade_usuarios', 'quantidade_lembrete', 'comentarios'));

        }
    /* Fim das funções dos usuários */

    /* Funções de Comentários */
    public function adicionar_comentario(Request $request, $id){

        $dados = $request->all();

        $tarefa = Tarefa::find($id);
        $usuario = User::find(Auth::id());

        $comentario = new Comentario_Tarefa();
        $comentario->id_usuario     = Auth::id();
        $comentario->id_tarefa      = $id;
        $comentario->comentario     = $dados['comentario'];
        $comentario->email  = Auth::user()->email;
        $comentario->save();

        //Event
        $evento = new NovoComentarioTarefa($tarefa, $usuario, $dados['comentario']);
        event($evento);

        /*======= Enviando Notificação do comentario =======
        try{
            $comentario->notify(new NovoComentarioMail());
        }
        catch(\Exception $e){ // Using a generic exception
             //Salvar no banco.
             //dd($e->message);
             DB::table('tbErros')->insert([
                [
                    'controller_metodo'               => 'TarefaController/adicionar_comentario',
                    'mensagem'           => $e->getMessage(),
                    'data'                => date( 'Y-m-d H:i:s')
                ]
            ]);
        }*/
        /*======= Fim Notificações =======*/

        return redirect()->route('backend.tarefa.editar',$id);
    }
    /* Fim das Funções de Comentários */
		public function apagarComentario(int $id_tarefa, int $id_comentario, Request $request){

	        $comentario = Comentario_Tarefa::find($id_comentario);

	        //apagar o registro.
	        $comentario->excluido = true;
            $comentario->excluido_por = FacadesAuth::id();
            $comentario->save();

	        //mensagem de sucesso
	        \Session::flash('aviso_mensagem', ['msg'=>'Comentário apagado com sucesso.', 'class'=>'']);

	        //return
	        return redirect()->route('backend.tarefa.editar',$id_tarefa);

	    }

    /* Funções dos Anexos */
        public function adicionar_anexo(Request $request, $id){
            $dados = $request->all();
            foreach ($request->anexos as $file) {
                $rand = rand(11111,99999);
                $diretorio = "anexos/".date( 'm' )."/";
                $ext = $file->guessClientExtension();
                $nomesemextensao = substr($file->getClientOriginalName(),0,-4);
                $nomeArquivo = $nomesemextensao . "_anexo_".$rand.".".$ext;
                $file->move($diretorio,$nomeArquivo);
                DB::table('tb_anexos_tarefas')->insert(
                        [
                            'id_usuario_postou' 	    => 	$dados['id_usuario_anexo'],
                            'id_tarefa' 				=>  $id,
                            'anexo' 					=>  $diretorio.''.$nomeArquivo,
                            'nome_arquivo'              =>  $nomesemextensao,
                            'tipo_arquivo'              =>  $ext
                        ]
                    );
            }

            $tarefa = Tarefa::find($id);
            $usuario     = User::find($tarefa->id_criado_por);

            $eventoNovoAnexo = new NovoAnexo($tarefa, $usuario);
            event($eventoNovoAnexo);

            return redirect()->route('backend.tarefa.editar',$id);
        }
    /* Fim das Funções dos Anexos*/
    public function apagarAnexo(int $id_tarefa, int $id_anexo, Request $request){

        $anexo = Anexo_Tarefa::find($id_anexo);

        //apagar o arquivo
        if($anexo->anexo){
            File::delete($anexo->anexo);
        }

        //apagar o registro.
        $anexo->delete();

        //mensagem de sucesso
        \Session::flash('aviso_mensagem', ['msg'=>'Arquivo apagado com sucesso.', 'class'=>'']);

        //return
        return redirect()->route('backend.tarefa.editar',$id_tarefa);

    }

    /* Funções de Seguidores */
        public function adicionar_seguidor(Request $request, $id){

            $dados = $request->all();
            $id_usuarios = User::all();

            foreach($id_usuarios as $registro){
                $id_registro = $registro->id;
                if( isset($dados['idseguidor'.$id_registro.'']) ){
                    // Adicionando o seguidor na tabela do banco de dados
                    DB::table('tb_seguidores_tarefas')->insert([
                            'id_usuario_postou'     =>  $dados['criado_por'],
                            'id_usuario_seguidor'   =>  $dados['idseguidor'.$id_registro.''],
                            'id_tarefa'             =>  $id
                        ]
                    );

                    $mailseguidor = SeguidorTarefa::where('tb_seguidores_tarefas.id_usuario_seguidor', '=', $dados['idseguidor'.$id_registro.''])
                    ->where('tb_seguidores_tarefas.id_tarefa', '=', $id)
                    ->leftJoin('tbTarefas',  'tb_seguidores_tarefas.id_tarefa',             '=', 'tbTarefas.id')
                    ->leftJoin('users',      'tb_seguidores_tarefas.id_usuario_seguidor',   '=', 'users.id')
                    ->select(
                        'users.name         as nome_usuario',
                        'users.sobrenome    as sobrenome_usuario',
                        'users.email',

                        'tbTarefas.id       as id',
                        'tbTarefas.titulo   as titulo'
                        )
                    ->first();
                    $mailseguidor->notify(new NovoSeguidorTarefa());
                }
            }

            return redirect()->route('backend.tarefa.editar',$id);
        }

        public function deletar_seguidor($id_tarefa, $id_seguidor){

            DB::table('tb_seguidores_tarefas')->where('tb_seguidores_tarefas.id_tarefa', '=', $id_tarefa)
            ->where('tb_seguidores_tarefas.id_usuario_seguidor', '=', $id_seguidor)
            ->delete();

            return redirect()->route('backend.tarefa.editar',$id_tarefa);
        }
    /* Fim das funções de seguidores */

    /* Funções do Dashboard */
        function LembreteEntregue(){
            $usuario_id = auth()->user()->id;
            $lembretes_entregue = Tarefa::Where('id_responsavel', $usuario_id)->where('status', '=', 'Finalizado')->count();
            return $lembretes_entregue;
        }
        function QuantidadeClientes(){
            $quantidade_clientes = Cliente::count();
            return $quantidade_clientes;
        }
        function QuantidadeUsuarios(){
            $quantidade_usuarios = User::Where('users.ativo', '=', '1')->count();
            return $quantidade_usuarios;
        }
        function QuantidadeLembrete(){
            $usuario_id = auth()->user()->id;
            $quantidade_lembrete = Tarefa::Where('id_responsavel', $usuario_id)->count();
            return $quantidade_lembrete;
        }
    /* Fim Funções do Dashboard */

    /* Controllers de Json */
        public function tipoTarefa(){

            $tipos      = TiposTarefa::orderBy('nome')
            ->select(
                'tb_tipostarefas.id as tipo_id',
                'tb_tipostarefas.nome as tipo_nome',
                'tb_tipostarefas.estimativa as tipo_estimativa'
            )->where('tb_tipostarefas.status',1)
            ->get();

            header('Content-Type: text/html; charset=utf-8');
            echo json_encode($tipos);

            return view('backend.tarefa.json.tipoTarefa', compact('tiposArray'));
        }

        public function projetoTarefa(){

            /*/
            SELECT COALESCE( c.nome_fantasia, c.nome ) , c.cliente_id, p.projeto, cd.dominio
            FROM clientes AS c
            INNER JOIN tb_projetos AS p ON c.cliente_id = p.cliente_id
            AND p.status = 'Ativo'
            LEFT JOIN tb_cliente_dominios AS cd ON cd.id_cliente = c.id
            AND p.id_dominio = cd.id_dominio
            AND cd.status = 'Ativo'
            WHERE c.status = '1'
            AND c.status_financeiro !=2
            LIMIT 0 , 1000
            */
            //DB::connection()->enableQueryLog();
            $projetos_clientes = Cliente::select(
                DB::raw('COALESCE( clientes.nome_fantasia, clientes.nome ) as nome_cliente'),
                DB::raw('COALESCE( tb_cliente_dominios.dominio, "sem dominio atrelado" ) as dominio_cliente'),
                'tb_projetos.id as id_cliente',
                'tb_projetos.projeto as nome_projeto'
            )->join('tb_projetos', function($join_projetos)
            {
                $join_projetos->on('clientes.cliente_id', '=', 'tb_projetos.cliente_id');
                $join_projetos->on('tb_projetos.status', '=',DB::raw("'Ativo'"));
            })->leftJoin('tb_cliente_dominios', function($join)
            {

                $join->on('clientes.id', '=', 'tb_cliente_dominios.id_cliente');
                $join->on('tb_projetos.id_dominio', '=', 'tb_cliente_dominios.id_dominio');
                //$join->on('tb_projetos.status', '=',DB::raw("'Ativo'"));
                //$join->on('tb_cliente_dominios.status', '=',DB::raw("'Ativo'"));

            })->where('clientes.status', '=', '1')
                ->where('clientes.status_financeiro', '!=', '2')
                ->orderBy('clientes.nome_fantasia')
                ->get();

            //dd(DB::getQueryLog());


            /*Cliente::Where('clientes.status_financeiro', '=', '0')
            ->where('clientes.status', '=', '1')
            ->leftJoin('tb_projetos', 'clientes.cliente_id', '=', 'tb_projetos.cliente_id')
            ->where('tb_projetos.status', '=', 'Ativo')
            ->leftJoin('tb_cliente_dominios', 'clientes.id', '=', 'tb_cliente_dominios.id_cliente')
            ->where('tb_cliente_dominios.status', '=', 'Ativo')
            ->select(
                'clientes.nome_fantasia as nome_cliente',
                'tb_cliente_dominios.dominio as dominio_cliente',
                'tb_projetos.id as id_cliente',
                'tb_projetos.projeto as nome_projeto'
            )
            ->orderby('clientes.nome_fantasia')
            ->get();
            //dd($projetos_clientes);*/

            header('Content-Type: text/html; charset=utf-8');
            echo json_encode($projetos_clientes);

            return view('backend.tarefa.json.projetoTarefa', compact('projetos_clientes'));
        }

        public function usuarioTarefa(){
            $setor_usuarios = User::Where('users.ativo', '=', '1')
            ->leftJoin('setor_usuarios', 'users.setor', '=', 'setor_usuarios.id')
            ->select(
                'users.name as nome_usuario',
                'users.sobrenome as sobrenome_usuario',
                'users.id as id_usuario',
                'users.image as imagem',
                'setor_usuarios.nome'
            )
            ->get();
            header('Content-Type: text/html; charset=utf-8');
            echo json_encode($setor_usuarios);
        }

        public function usuarioTarefaSetor($id_setor){
            $setor_usuarios = User::Where('setor', $id_setor)
                                ->where('users.ativo', '=', '1')
                                ->leftJoin('setor_usuarios', 'users.setor', '=', 'setor_usuarios.id')
                                ->select(
                                    'users.name as nome_usuario',
                                    'users.sobrenome as sobrenome_usuario',
                                    'users.id as id_usuario',
                                    'users.image as imagem',
                                    'setor_usuarios.nome'
                                )
                                ->get();

            header('Content-Type: text/html; charset=utf-8');
            echo json_encode($setor_usuarios);

            return view('backend.tarefa.json.usuarioTarefaSetor', compact('setor_usuarios'));
        }

        public function setorTarefa(){
            $setores = SetorUsuario::Select(
                'setor_usuarios.id as id_setor',
                'setor_usuarios.nome as nome_setor',
                'setor_usuarios.email as email_setor',
                'setor_usuarios.descricao as descricao_setor'
            )
            ->get();

            header('Content-Type: text/html; charset=utf-8');
            echo json_encode($setores);

            return view('backend.tarefa.json.usuarioTarefa', compact('setores'));
        }

        public function statusTarefa(){
            $status = Status::Where('tb_status.status', '=', 'Ativo')
            ->select(
                'tb_status.id as id_status',
                'tb_status.nome as nome_status',
                'tb_status.status as status'
            )
            ->get();

            header('Content-Type: text/html; charset=utf-8');
            echo json_encode($status);

            return view('backend.tarefa.json.statusTarefa', compact('status'));
        }
    /* Fim Controllers de Json */

        public function downloadAnexos($id){
            $anexos = DB::table('tb_anexos_tarefas')->where('tb_anexos_tarefas.id_tarefa', '=', $id)
                ->leftJoin('users', 'tb_anexos_tarefas.id_usuario_postou', '=', 'users.id')
                ->select(
                    'tb_anexos_tarefas.id as id_anexo',
                    'tb_anexos_tarefas.id_usuario_postou',
                    'tb_anexos_tarefas.anexo',
                    'tb_anexos_tarefas.nome_arquivo',
                    'tb_anexos_tarefas.tipo_arquivo',
                    'users.name',
                    'users.sobrenome',
                    'users.image'
                )
                ->orderBy('tb_anexos_tarefas.id', 'DESC')
                ->get();
            //dd($anexos);
            $diretorio = public_path("anexos/compactados/".date( 'm' )."/");

            if(!File::exists($diretorio)){
                File::makeDirectory($diretorio, $mode = 0777, true, true);
            }
            $zip_file = $diretorio . 'anexos' . $id . '.zip'; // Name of our archive to download

            // Initializing PHP class
            $zip = new ZipArchive;
            $zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
            foreach($anexos as $allanexos){
                $path = public_path($allanexos->anexo);
                $zip->addFile($path, $allanexos->anexo);
            }

            // Adding file: second parameter is what will the path inside of the archive
            // So it will create another folder called "storage/" inside ZIP, and put the file there.

            $zip->close();

            // We return the file immediately after download
            return response()->download($zip_file);
        }

}
