<?php

namespace App\Listeners;

use App\Events\ConcluirTarefa;
use App\Mail\ConcluirTarefa as MailConcluirTarefa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EnviaEmailConcluirTarefa implements ShouldQueue
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
    public function handle(ConcluirTarefa $event)
    {
        $url        = route('backend.tarefa.editar', $event->tarefa->id);
        $titulo     = $event->tarefa->titulo;
        $criadopor_nome     = $event->criadopor->name . ' ' . $event->criadopor->sobrenome;
        $responsavel_nome     = $event->responsavel->name . ' ' . $event->responsavel->sobrenome;
        $criadopor_email = $event->criadopor->email;

        $seguidor = DB::table('tb_seguidores_tarefas')->where('id_tarefa', $event->tarefa->id)
        ->leftJoin('users', 'tb_seguidores_tarefas.id_usuario_seguidor', '=', 'users.id')
        ->select('users.email')
        ->get();

        $Arr = Arr::pluck($seguidor,'email');

        $email = new MailConcluirTarefa(
                $titulo,
                $responsavel_nome,
                $criadopor_nome,
                $url
            );
        $email->subject('[LD] Tarefa ENTREGUE #'.$event->tarefa->id.' - ' . $titulo);
        $quando = now()->addSeconds(10);
        Mail::to($criadopor_email)->bcc($Arr)->later($quando, $email);
    }
}
