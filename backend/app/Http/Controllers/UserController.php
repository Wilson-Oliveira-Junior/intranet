<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::with('time')->get(); // Carrega também a equipe associada
        Log::info('Lista de usuários retornada', ['total' => $usuarios->count()]);
        return response()->json($usuarios);
    }

    public function getPermissions()
    {
        $permissions = [
            'canEdit' => true,
            'canDelete' => false,
        ];
        Log::info('Permissões retornadas', ['permissions' => $permissions]);
        return response()->json($permissions);
    }

    public function getMembersByTeam(Request $request)
    {
        $request->validate([
            'team' => 'required|string',
        ]);

        $members = User::where('time_id', $request->team)->with('time')->get(); // Usar time_id
        Log::info('Membros da equipe retornados', ['team' => $request->team, 'total' => $members->count()]);
        return response()->json($members);
    }
}
