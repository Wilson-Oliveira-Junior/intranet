<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CronogramaController;
use App\Http\Controllers\TarefaController;

// Rota para dados do dashboard
Route::get('/dashboard-data', function () {
    return response()->json([
        'quadro1' => 0,
        'quadro2' => 0,
        'quadro3' => 15,
        'quadro4' => 321,
    ]);
});

// Agrupando as rotas com o middleware 'api'
Route::middleware('api')->group(function () {
    // Rotas para autenticação

    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/register', [RegisterController::class, 'register']);

    // Rotas para usuários
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/user-permissions', [UserController::class, 'getPermissions']);

    // Rotas para cronograma
    Route::get('/cronograma', [CronogramaController::class, 'index']);
    // Route::post('/tarefa/finalizar/{id}', [TarefaController::class, 'finalizar']);
    // Route::delete('/tarefa/remover/{id}', [TarefaController::class, 'remover']);


});
