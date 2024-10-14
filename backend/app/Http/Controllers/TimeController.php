<?php

namespace App\Http\Controllers;

use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimeController extends Controller
{
    // Listar todos os times
    public function index()
    {
        $times = Time::all();
        return response()->json($times);
    }

    // Criar um novo time
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $time = Time::create($request->all());
        return response()->json($time, 201);
    }

    // Visualizar um time específico
    public function show($id)
    {
        $time = Time::findOrFail($id);
        return response()->json($time);
    }

    // Atualizar um time específico
    public function update(Request $request, $id)
    {
        $time = Time::findOrFail($id);
        $time->update($request->all());
        return response()->json($time);
    }

    // Remover um time específico
    public function destroy($id)
    {
        $time = Time::findOrFail($id);
        $time->delete();
        return response()->json(['message' => 'Time removido com sucesso'], 204);
    }
}
