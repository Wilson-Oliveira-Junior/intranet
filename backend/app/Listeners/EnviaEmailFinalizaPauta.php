<?php

namespace App\Listeners;

use App\Events\FinalizaPauta;
use App\Mail\FinalizaPauta as MailFinalizaPauta;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviaEmailFinalizaPauta implements ShouldQueue
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
    public function handle(FinalizaPauta $event)
    {
        $url = route('backend.pauta.index');
        $titulo = $event->pauta->titulo;
        $cliente = $event->pauta->projeto->cliente->nome_fantasia;
        $responsavel_nome = $event->pauta->responsavel->name . ' ' . $event->pauta->responsavel->sobrenome;
        $criadopor = $event->pauta->criadopor->name . ' ' . $event->pauta->criadopor->sobrenome;
        $criadopor_email = $event->pauta->criadopor->email;
        $vcomentario = $event->pauta->comentario;
		if(is_null($vcomentario)){$vcomentario='';}
        $assunto = "[LD] Pauta FINALIZADA: " . $titulo;

        //dd($responsavel_email);
        $email = new MailFinalizaPauta(
            $url,
            $cliente,
            $responsavel_nome,
            $criadopor,
            $titulo,
            $vcomentario
        );

        $email->subject($assunto);

        //$email->priority($idUrgencia);
        $quando = now()->addSeconds(20);
        Mail::to($criadopor_email)->later($quando, $email);
    }
}
