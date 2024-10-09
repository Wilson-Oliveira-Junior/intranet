<?php

namespace App\Listeners;

use App\Events\NovaPauta;
use App\Mail\NovaPauta as MailNovaPauta;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class EnviaEmailPauta implements ShouldQueue
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
     * @param  NovaPauta  $event
     * @return void
     */
    public function handle(NovaPauta $event)
    {
        //
        //dd($event);
        $url = route('backend.pauta.index');
        $titulo = $event->pauta->titulo;
        $cliente = $event->pauta->projeto->cliente->nome_fantasia;
        $responsavel_email = $event->pauta->responsavel->email;
        $responsavel_nome = $event->pauta->responsavel->name . ' ' . $event->pauta->responsavel->sobrenome;
        $criadopor = $event->pauta->criadopor->name . ' ' . $event->pauta->criadopor->sobrenome;
        $datadesejada = date('d/m/Y', strtotime($event->pauta->data_desejada));
        $hoje = (new Carbon())->format('Y-m-d');
        $vidUrgencia = $event->pauta->idUrgencia;

        if($vidUrgencia == 1){
            $urgencia = "Tem que ser feito imediatamente.";
            $assunto = "[LD] URGENTE Pauta: " . $titulo;
        }elseif($vidUrgencia == 2){
            $urgencia = "Tem que ser feito no mesmo dia.";
            $assunto = "[LD] HOJE Pauta: " . $titulo;
        }elseif($vidUrgencia == 3){
            $urgencia = "Tem que ser feito até a data estipulada.";
            if($event->pauta->data_desejada == $hoje){
                $assunto = "[LD] HOJE Pauta: " . $titulo;
            }elseif($event->pauta->data_desejada < $hoje){
                $assunto = "[LD] ATRASADA Pauta: " . $titulo;
            }else{
                $assunto = "[LD] Pauta: " . $titulo . " - " . $datadesejada;
            }
        }else{
            $urgencia = "Encaixar no cronograma.";
            if($event->pauta->data_desejada == $hoje){
                $assunto = "[LD] HOJE Pauta: " . $titulo;
            }elseif($event->pauta->data_desejada < $hoje){
                $assunto = "[LD] ATRASADA Pauta: " . $titulo;
            }else{
                $assunto = "[LD] Pauta: " . $titulo . " - " . $datadesejada;
            }
        }

        //dd($responsavel_email);
        $email = new MailNovaPauta(
            $url,
            $cliente,
            $urgencia,
            $responsavel_nome,
            $criadopor,
            $titulo,
            (string)$datadesejada
        );

        $email->subject($assunto);

        //$email->priority($idUrgencia);
        $quando = now()->addSeconds(20);
        Mail::to($responsavel_email)->later($quando, $email);
        //dd('Ok');
    }
}
