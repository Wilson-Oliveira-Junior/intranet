<?php

namespace App\Http\Controllers;

use App\Models\Cronograma;
use App\Models\Tarefa; // Importando o modelo Tarefa corretamente
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
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|string|in:abertas,em andamento,finalizada',
            'seguidores' => 'nullable|array',
            'seguidores.*' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        $tarefaData = $request->all();
        $tarefaData['seguidores'][] = $request->user()->id;
        $tarefa = Tarefa::create($tarefaData);
        return response()->json($tarefa, 201);
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
