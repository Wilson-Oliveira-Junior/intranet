<?php
//namespace App\Helpers;
use Carbon\Carbon;
use App\Tarefa;
use App\Cliente;
use App\SetorUsuario;
use App\User;
use App\Segmento;
use App\Evento;

/** php function to generate random string of given length **/
function ldDashboardQuadro1($id){
    //
    return Tarefa::Where('id_responsavel', $id)->where('status', '=', 'Finalizado')->count();
}

function ldDashboardQuadro2($id){
    return Tarefa::Where('id_responsavel', $id)->count();
}

function ldDashboardQuadro3(){
    return User::Where('users.ativo', '=', '1')->count();
}

function ldDashboardQuadro4(){
    return Cliente::Where('status','1')->count();
}

function LDSetores(){
    return SetorUsuario::all();
}

function LDSegmentos(){
    return Segmento::all();
}
function retornarDiaDaSemana(int $diaSemana){
    if($diaSemana == 1){
        return 'Seg';
    }

    if($diaSemana == 2){
        return 'Ter';
    }

    if($diaSemana == 3){
        return 'Qua';
    }

    if($diaSemana == 4){
        return 'Qui';
    }

    if($diaSemana == 5){
        return 'Sex';
    }

    if($diaSemana == 6){
        return 'Sab';
    }

    if($diaSemana == 7){
        return 'Dom';
    }
}

function retornarDiaDaSemanaCompleto(int $diaSemana){
    if($diaSemana == 1){
        return 'segunda';
    }

    if($diaSemana == 2){
        return 'terca';
    }

    if($diaSemana == 3){
        return 'quarta';
    }

    if($diaSemana == 4){
        return 'quinta';
    }

    if($diaSemana == 5){
        return 'sexta';
    }

    if($diaSemana == 6){
        return 'sabado';
    }

    if($diaSemana == 7){
        return 'domingo';
    }
}
function retornarNomeMes($intMes){
    $nome_mes = '';
    switch ($intMes) {
        case "01":
            $nome_mes = 'Janeiro';
            break;
        case "02":
            $nome_mes = 'Fevereiro';
            break;
        case "03":
            $nome_mes = 'Março';
            break;
        case "04":
            $nome_mes = 'Abril';
            break;
        case "05":
            $nome_mes = 'Maio';
            break;
        case "06":
            $nome_mes = 'Junho';
            break;
        case "07":
            $nome_mes = 'Julho';
            break;
        case "08":
            $nome_mes = 'Agosto';
            break;
        case "09":
            $nome_mes = 'Setembro';
            break;
        case "10":
            $nome_mes = 'Outubro';
            break;
        case "11":
            $nome_mes = 'Novembro';
            break;
        case "12":
            $nome_mes = 'Dezembro';
            break;
    }

    return $nome_mes;
}

function retornarNomeMesIngles($intMes){
    $mes_date = '';
    switch ($intMes) {
        case "01":
            $mes_date = 'January';
            break;
        case "02":
            $mes_date = 'February';
            break;
        case "03":
            $mes_date = 'March';
            break;
        case "04":
            $mes_date = 'April';
            break;
        case "05":
            $mes_date = 'May';
            break;
        case "06":
            $mes_date = 'June';
            break;
        case "07":
            $mes_date = 'July';
            break;
        case "08":
            $mes_date = 'August';
            break;
        case "09":
            $mes_date = 'September';
            break;
        case "10":
            $mes_date = 'October';
            break;
        case "11":
            $mes_date = 'November';
            break;
        case "12":
            $mes_date = 'December';
            break;
    }

    return $mes_date;
}

function notificationsEvento($id){
    $hoje = (new Carbon())->format('Y-m-d');
    return Evento::Where('id_usuario', $id)->Where('evento_data_inicio', '<=', $hoje)->Where('evento_data_fim', '>=', $hoje)
            ->select('nome',
                    DB::raw('TIMESTAMPDIFF( HOUR , created_at, now( ) ) AS diferenca')
            )->get();
}

function notificationsErros($id){
    if($id == 3){
        $hoje = (new Carbon())->format('Y-m-d');
        return DB::table('tbErros')->where('data', '<=', $hoje . ' 23:59:59')->where('data', '>=', $hoje  . ' 00:00:00')
        ->select('idErro', 'controller_metodo', 'mensagem',
                DB::raw('TIMESTAMPDIFF( HOUR , data, now( ) ) AS diferenca')
        )->get();
    }else{
        return null;
    }
}

function notificationsTarefa($id){
    $hoje = Carbon::now()->dayOfWeek;
    $hojeformatado = (new Carbon())->format('Y-m-d');
    $date = Carbon::parse($hojeformatado . ' 08:00:00');
    $now = Carbon::now()->format('Y-m-d h:i:s');
    //dd($now);
    $diff = $date->diffInHours($now);
    //dd($diff);

    return  DB::table('tb_cronogramas')
                    ->join('tbTarefas', 'tb_cronogramas.id_tarefa',     '=', 'tbTarefas.id')
                    ->where('tbTarefas.id_responsavel', '=', $id)
                    ->where('tb_cronogramas.data', '=', $hojeformatado)
                    ->select(
                        'tbTarefas.id',
                        'tbTarefas.titulo',
                        'tbTarefas.status',
                        'tb_cronogramas.id_cronograma',
                        'tb_cronogramas.data',
                        DB::raw(" ('$diff') AS diferenca")
                    )
                    ->get();
}

function notificationsSoma($id){
    $hojeDay = Carbon::now()->dayOfWeek;
    $hoje = (new Carbon())->format('Y-m-d');

    $tarefas    = DB::table('tb_cronogramas')
                    ->join('tbTarefas', 'tb_cronogramas.id_tarefa',     '=', 'tbTarefas.id')
                    ->where('tbTarefas.id_responsavel', '=', $id)
                    ->where('tb_cronogramas.data', '=', $hoje)
                    ->select(
                        'tbTarefas.id',
                        'tbTarefas.titulo',
                        'tbTarefas.status',
                        'tb_cronogramas.id_cronograma',
                        'tb_cronogramas.data'
                    )
                    ->count();

    $eventos    = Evento::Where('id_usuario', $id)->Where('evento_data_inicio', '<=', $hoje)->Where('evento_data_fim', '>=', $hoje)->count();

    if($id == 1 || $id == 3){
    $erros = DB::table('tbErros')->where('data', '<=', $hoje . ' 23:59:59')->where('data', '>=', $hoje  . ' 00:00:00')
        ->select('idErro', 'controller_metodo', 'mensagem',
                DB::raw('TIMESTAMPDIFF( HOUR , data, now( ) ) AS diferenca')
        )->count();
    }else{$erros=0;}

    $soma = $tarefas + $eventos + $erros;
    //dd($soma);

    return $soma;
}

?>
