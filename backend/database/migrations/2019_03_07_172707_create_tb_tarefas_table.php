<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbTarefasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbTarefas', function (Blueprint $table) {
            $table->increments('id');
            
            // Campos
            $table->string('titulo');
            $table->mediumText('descricao');
            $table->string('inicio_tarefa');

            // Tabela Relacionadas
            $table->integer('id_responsavel');
            $table->integer('id_equipe');
            $table->integer('id_tipo');
            $table->integer('id_projeto');
            $table->integer('id_status');
            $table->integer('id_criado_por');

            // Datas da Tarefa
            $table->date('data_desejada');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->date('data_criado');

            // Tempo da tarefa
            $table->string('tempo_esperado');
            $table->string('tempo_trabalhado');

            // Status
            $table->enum('status',['Producao','Pendente','Finalizado'])->default('Producao');

            // Data e Hora da criação
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbTarefas');
    }
}
