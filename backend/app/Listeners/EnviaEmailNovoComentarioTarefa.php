<?php

namespace App\Listeners;

use App\Events\NovoComentarioTarefa;
use App\Mail\NovoComentarioTarefa as MailNovoComentarioTarefa;
use App\SeguidorTarefa;
use App\SetorUsuario;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviaEmailNovoComentarioTarefa implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NovoComentarioTarefa $event)
    {
        //
        $arrEmail = [];
        $flCriadoPor = false;
        $flResponsavel = false;
        $flResponsavelTarefa = false;
        $idResponsavelTarefa = '';
        $idCriadoPor = '';
        $idTarefa = $event->vTarefa->id;
        $titulo = $event->vTarefa->titulo;
        $idResponsavelPeloComentario = $event->vUsuario->id;
        $responsavel_comentairo = $event->vUsuario->name;
        $responsavel_comentario_sobrenome = $event->vUsuario->sobrenome;
        $comentario = $event->vComentario;
        $url = route('backend.tarefa.editar', $event->vTarefa->id);

        $novocomentario = new MailNovoComentarioTarefa(
            $titulo, $responsavel_comentairo,
            $responsavel_comentario_sobrenome,
            $comentario, $url
        );

        if(is_null($event->vTarefa->id_responsavel)){
            $idEquipe = $event->vTarefa->id_equipe;
            $setorResponsavel = SetorUsuario::find($idEquipe);
            $arrEmail[$setorResponsavel->id] = [
                'email' => $setorResponsavel->email
            ];
        }else{
            if($idResponsavelPeloComentario != $event->vTarefa->id_responsavel){
                $responsavelTarefa = User::find($event->vTarefa->id_responsavel);
                $arrEmail[$responsavelTarefa->id] = [
                    'email' => $responsavelTarefa->email
                ];
                $idResponsavelTarefa = $event->vTarefa->id_responsavel;
                $flResponsavelTarefa = true;
            }else{
                $arrEmail[$idResponsavelPeloComentario] = [
                    'email' => $event->vUsuario->email
                ];
                $flResponsavel = true;
            }
        }

        if($idResponsavelPeloComentario != $event->vTarefa->id_criado_por){
            $criadorTarefa = User::find($event->vTarefa->id_criado_por);
            $arrEmail[$criadorTarefa->id] = [
                'email' => $criadorTarefa->email
            ];
            $flCriadoPor = true;
            $idCriadoPor = $criadorTarefa->id;
        }

        if($idResponsavelPeloComentario != $event->vTarefa->id_criado_por && $idResponsavelPeloComentario != $event->vTarefa->id_responsavel){
            $arrEmail[$idResponsavelPeloComentario] = [
                'email' => $event->vUsuario->email
            ];
            $flResponsavel = true;
        }

        $seguidores = SeguidorTarefa::from('tb_seguidores_tarefas as st')
                ->join('users as u', 'st.id_usuario_seguidor', 'u.id')
                ->where('st.id_tarefa', $idTarefa)
                ->when($flResponsavelTarefa, function($q) use($idResponsavelTarefa){
                    $q->where('st.id_usuario_seguidor', '!=', $idResponsavelTarefa);
                })
                ->when($flCriadoPor, function($q) use($idCriadoPor){
                    $q->where('st.id_usuario_seguidor', '!=', $idCriadoPor);
                })
                ->when($flResponsavel, function($q) use($idResponsavelPeloComentario){
                    $q->where('st.id_usuario_seguidor', '!=', $idResponsavelPeloComentario);
                })
                ->select('u.email')
                ->get();


        $novocomentario->subject('[LD] NOVO Comentário na Tarefa #'.$idTarefa.' - ' . $titulo);
        $quando = now()->addSeconds(25);

        Mail::to($arrEmail)->cc($seguidores)->later($quando, $novocomentario);
    }
}
