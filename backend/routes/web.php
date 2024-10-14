<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CronogramaController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\GutController;
use App\Models\Time;
use App\Models\User;

// Rota para dados do dashboard
Route::get('/dashboard-data', function () {
    return response()->json([
        'quadro1' => 0,
        'quadro2' => 0,
        'quadro3' => 15,
        'quadro4' => 321,
    ]);
});

// Agrupando as rotas
Route::middleware('api')->group(function () {
    // Rotas para autenticação
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/register', [RegisterController::class, 'register']);

    // Rotas para usuários
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/user-permissions', [UserController::class, 'getPermissions']);
    Route::post('/usuarios', [UserController::class, 'store']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);

    // Rotas para cronograma
    Route::get('/cronograma', [CronogramaController::class, 'index']);
    Route::post('/cronograma', [CronogramaController::class, 'store']);
    Route::get('/cronograma/{id}', [CronogramaController::class, 'show']);
    Route::put('/cronograma/{id}', [CronogramaController::class, 'update']);
    Route::delete('/cronograma/{id}', [CronogramaController::class, 'destroy']);

    // Rotas para tarefas
    Route::get('/tarefas', [TarefaController::class, 'index']);
    Route::post('/tarefas', [TarefaController::class, 'store']);
    Route::get('/tarefas/{id}', [TarefaController::class, 'show']);
    Route::put('/tarefas/{id}', [TarefaController::class, 'update']);
    Route::delete('/tarefas/{id}', [TarefaController::class, 'destroy']);
    Route::put('/tarefas/{id}/atualizar-status', [TarefaController::class, 'atualizarStatus']);

    // Rotas para GUT
    Route::get('/GUT', [GutController::class, 'index']);

    // Rotas para Times
    Route::get('/times', function () {
        return Time::all(); // Retorna todos os times
    });

    // Rotas para Membros
    Route::get('/members', function (Request $request) {
        $team = $request->query('team');
        return User::where('team', $team)->get(); // Busca membros pelo time
    });
});
