<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExcluidoExcluidoPorTbComentariosTarefas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb_comentarios_tarefas', function (Blueprint $table) {
            //
            $table->boolean('excluido')->default(false);
            $table->integer('excluido_por')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_comentarios_tarefas', function (Blueprint $table) {
            //
            $table->dropColumn('excluido');
            $table->dropColumn('excluido_por');
        });
    }
}
