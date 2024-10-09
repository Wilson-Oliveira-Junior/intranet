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

    // Criar nova tarefa
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
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

    // Finalizar uma tarefa
    public function finalizar($id)
    {
        $tarefa = Tarefa::findOrFail($id);
        $tarefa->update(['status' => 'finalizada']); // Ajuste conforme sua lógica
        return response()->json($tarefa);
    }
}

