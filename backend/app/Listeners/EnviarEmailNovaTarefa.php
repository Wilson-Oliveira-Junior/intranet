<?php

namespace App\Listeners;

use App\Events\NovaTarefa;
use App\Mail\NovaTarefa as MailNovaTarefa;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviarEmailNovaTarefa implements ShouldQueue
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
     * @param  NovaTarefa  $event
     * @return void
     */
    public function handle(NovaTarefa $event)
    {
        //
        $url        = route('backend.tarefa.editar', $event->tarefa->id);
        $titulo     = $event->tarefa->titulo;
        $aberta     = $event->aberta;
        $aberta_sobrenome     = $event->aberta_sobrenome;
        $responsavel     = $event->responsavel;
        $responsavel_sobrenome     = $event->responsavel_sobrenome;
        $setor = $event->setor;
        $responsavel_email = $event->responsavel_email;

        $email = new MailNovaTarefa(
                $titulo,
                $responsavel,
                $setor,
                $responsavel_sobrenome,
                $aberta,
                $aberta_sobrenome,
                $url
            );
        $email->subject('[LD] NOVA Tarefa #'.$event->tarefa->id.' - ' . $titulo);
        $quando = now()->addSeconds(10);
        Mail::to($responsavel_email)->later($quando, $email);
    }
}
