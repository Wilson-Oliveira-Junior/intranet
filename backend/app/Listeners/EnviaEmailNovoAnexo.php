<?php

namespace App\Listeners;

use App\Events\NovoAnexo as EventsNovoAnexo;
use App\Mail\NovoAnexo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviaEmailNovoAnexo implements ShouldQueue
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
    public function handle(EventsNovoAnexo $event)
    {
        //
        //dd($event);
        $email_novo_anexo = new NovoAnexo($event->tarefa, $event->usuario);
        $email_novo_anexo->subject('[LD] Anexo adicionado na tarefa #'.$event->tarefa->id.' - ' . $event->tarefa->titulo);
        $quando = now()->addSeconds(20);
        Mail::to($event->usuario->email)->later($quando, $email_novo_anexo);
    }
}
