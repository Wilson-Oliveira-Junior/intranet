<?php

namespace App\Http\Controllers;

use App\Models\User; // Inclua o modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::all(); // Utilize o modelo User para melhor legibilidade
        Log::info('Lista de usuários retornada', ['total' => $usuarios->count()]);
        return response()->json($usuarios);
    }

    public function getPermissions()
    {
        // Retorna as permissões do usuário logado
        // Você pode ajustar esta lógica conforme necessário
        $permissions = [
            'canEdit' => true,
            'canDelete' => false,
            // Adicione mais permissões conforme necessário
        ];
        Log::info('Permissões retornadas', ['permissions' => $permissions]);
        return response()->json($permissions);
    }
}
