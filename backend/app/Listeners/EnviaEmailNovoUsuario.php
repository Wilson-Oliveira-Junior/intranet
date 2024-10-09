<?php

namespace App\Listeners;

use App\Events\NovoUsuario;
use App\Mail\NovoUsuario as MailNovoUsuario;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviaEmailNovoUsuario implements ShouldQueue
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
     * @param  NovoUsuario  $event
     * @return void
     */
    public function handle(NovoUsuario $event)
    {
        //
        $bemvindo = ($event->user->sexo == 'M')?'Bem-vindo':'Bem-vinda';
        $apelido = (!is_null($event->user->apelido))?$event->user->apelido:$event->user->name;
        $equipe = User::where('setor', $event->user->setor)->where('ativo', 1)->where('id', '!=', $event->user->id)->select('name')->get();
        $arrEquipe = $equipe->pluck('name');
        $arrEquipe->all();
        //dd($arrEquipe);
        $email = $event->user->email;
        $usuario_rede = $event->user->user_rede;

        $montaemail = new MailNovoUsuario($bemvindo, $apelido, $arrEquipe, $email, $usuario_rede);

        $montaemail->subject('[LD] Seja ' . strtolower($bemvindo) . ' ' . $apelido);
        //dd('Ok');
        $quando = now()->addSeconds(40);
        Mail::to($email)->bcc('staff@logicadigital.info')->later($quando, $montaemail);

    }
}
