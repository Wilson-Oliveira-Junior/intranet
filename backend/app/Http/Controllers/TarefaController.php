<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TarefaController extends Controller
{
    // Listar todas as tarefas
    public function index()
    {
        return Tarefa::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|string|in:abertas,em andamento,finalizada,backlog', // Adicionado backlog
            'equipe_id' => 'nullable|integer', // Adicionando o campo para equipe
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Se não houver equipe definida, define como backlog
        if (empty($request->equipe_id)) {
            $request->merge(['status' => 'backlog']);
        }

        $tarefa = Tarefa::create($request->all());
        return response()->json($tarefa, 201);
    }

    // Visualizar uma tarefa específica
    public function show($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        return response()->json($tarefa);
    }

    // Atualizar o status e tempo trabalhado
    public function atualizarStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:abertas,em andamento,finalizada',
            'tempo_trabalhado' => 'nullable|integer', // Tempo em segundos
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tarefa = Tarefa::findOrFail($id);

        // Se houver tempo trabalhado, adiciona ao total
        if ($request->has('tempo_trabalhado')) {
            $tarefa->total_trabalhado += $request->tempo_trabalhado;
        }

        $tarefa->status = $request->status;
        $tarefa->save();

        return response()->json(['message' => 'Status e tempo atualizado com sucesso']);
    }

    // Atualizar uma tarefa específica
    public function update(Request $request, $id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update($request->all());
        return response()->json($tarefa);
    }

    // Remover uma tarefa específica
    public function destroy($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
        return response()->json(null, 204);
    }
}
