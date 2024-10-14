<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GutController extends Controller {
    public function index() {
        return response()->json([
            'tarefas' => [
                [
                    'id' => 1,
                    'name' => '[Rádio Laser] Página contato',
                    'G' => 5,
                    'U' => 5,
                    'T' => 5,
                    'P' => 125,
                    'dataTarefa' => '26/05/2022',
                    'dataDesejada' => '27/10/2023',
                    'status' => 'Aguardando Cronograma',
                ],
                // Adicione mais tarefas aqui
            ]
        ]);
    }

    public function show($id) {
        // Lógica para mostrar uma tarefa específica
    }

    public function store(Request $request) {
        // Lógica para armazenar uma nova tarefa
    }

    public function update(Request $request, $id) {
        // Lógica para atualizar uma tarefa existente
    }

    public function destroy($id) {
        // Lógica para excluir uma tarefa
    }
}
