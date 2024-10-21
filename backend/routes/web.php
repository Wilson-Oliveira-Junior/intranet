<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CronogramaController;
use App\Http\Controllers\TarefaController;
use App\Http\Controllers\GutController;
use App\Http\Controllers\CsrfCookieController;
use App\Models\Time;
use App\Models\User;

// Rota para obter o CSRF Cookie
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'getCookie']);

// Rotas públicas
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

// Agrupando as rotas protegidas por autenticação e middleware 'auth:sanctum'
Route::middleware(['auth:sanctum'])->group(function () {
    // Rotas de logout
    Route::post('/logout', [LoginController::class, 'logout']);

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
        return Time::all();
    });

    // Rotas para Membros
    Route::get('/members', function (Request $request) {
        $team = $request->query('team');
        return User::where('team', $team)->get();
    });
});
