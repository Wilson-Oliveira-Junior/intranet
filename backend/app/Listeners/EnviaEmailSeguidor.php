<?php

namespace App\Listeners;

use App\Events\Seguidor;
use App\Mail\Seguidor as MailSeguidor;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviaEmailSeguidor implements ShouldQueue
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
     * @param  Seguidor  $event
     * @return void
     */
    public function handle(Seguidor $event)
    {
        //
        //dd($event->seguidor->id);
        $url        = route('backend.tarefa.editar', $event->seguidor->id);
        $id_tarefa  = $event->seguidor->id;
        $titulo     = $event->seguidor->titulo;
        $nome       = $event->seguidor->nome_usuario;
        $sobrenome  = $event->seguidor->sobrenome_usuario;

        $email_seg = new MailSeguidor(
                $url,
                $id_tarefa,
                $titulo,
                $nome,
                $sobrenome
            );
        $email_seg->subject('[LD] Você agora está seguindo a tarefa #'.$event->seguidor->id.' - ' . $titulo);
        $quando = now()->addSeconds(10);
        Mail::to($event->seguidor->email)->later($quando, $email_seg);
    }
}
