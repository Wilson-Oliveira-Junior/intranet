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
        return Tarefa::with('cronograma', 'user')->get(); // Carrega relacionamentos
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|string|in:Aguardando Cronograma,Trabalhando,Alocado,Tarefa Reaberta,Backlog', // Novos status
            'equipe_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Se equipe_id não for fornecido, definir status como Backlog
        if (empty($request->equipe_id)) {
            $request->merge(['status' => 'Backlog']);
        }

        $tarefa = Tarefa::create($request->all());
        return response()->json($tarefa, 201);
    }

    public function show($id)
    {
        $tarefa = Tarefa::with('cronograma', 'user')->findOrFail($id);
        return response()->json($tarefa);
    }

    public function atualizarStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:Aguardando Cronograma,Trabalhando,Alocado,Tarefa Reaberta,Backlog', // Novos status
            'tempo_trabalhado' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tarefa = Tarefa::findOrFail($id);
        if ($request->has('tempo_trabalhado')) {
            $tarefa->total_trabalhado += $request->tempo_trabalhado;
        }

        $tarefa->status = $request->status;
        $tarefa->save();

        return response()->json(['message' => 'Status e tempo atualizado com sucesso']);
    }

    public function update(Request $request, $id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update($request->all());
        return response()->json($tarefa);
    }

    public function destroy($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->delete();
        return response()->json(['message' => 'Tarefa removida com sucesso'], 204);
    }
}
