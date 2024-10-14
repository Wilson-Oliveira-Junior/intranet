<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTbSeguidoresTarefas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_seguidores_tarefas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_usuario_postou');
            $table->integer('id_usuario_seguidor');
            $table->integer('id_tarefa');
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
        //
    }
}
