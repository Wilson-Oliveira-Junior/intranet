<?php

namespace App\Http\Controllers;

use App\Models\Cronograma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CronogramaController extends Controller
{
    public function index()
    {
        Log::info('Cronograma index chamado');
        return response()->json(Cronograma::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'data' => 'required|date',
            'categoria' => 'required|string|in:normal,urgente', // Validação para categoria
            'cliente' => 'required|string' // Validação para o nome do cliente
        ]);

        $cronograma = Cronograma::create($validated);
        Log::info('Novo cronograma criado', ['cronograma' => $cronograma]);
        return response()->json($cronograma, 201);
    }

    public function show($id)
    {
        $cronograma = Cronograma::findOrFail($id);
        Log::info('Cronograma mostrado', ['id' => $id]);
        return response()->json($cronograma);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titulo' => 'sometimes|required|string',
            'data' => 'sometimes|required|date',
            'categoria' => 'sometimes|required|string|in:normal,urgente',
            'cliente' => 'sometimes|required|string',
        ]);

        $cronograma = Cronograma::findOrFail($id);
        $cronograma->update($validated);
        Log::info('Cronograma atualizado', ['cronograma' => $cronograma]);
        return response()->json($cronograma);
    }

    public function destroy($id)
    {
        try {
            Cronograma::destroy($id);
            Log::info('Cronograma deletado', ['id' => $id]);
            return response()->json(['message' => 'Cronograma deletado com sucesso.'], 204);
        } catch (\Exception $e) {
            Log::error('Erro ao deletar cronograma: ' . $e->getMessage());
            return response()->json(['error' => 'Erro ao deletar o cronograma.'], 500);
        }
    }
}
