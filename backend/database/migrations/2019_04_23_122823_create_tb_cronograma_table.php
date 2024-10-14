<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbCronogramaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cronogramas', function (Blueprint $table) {
            $table->increments('id_cronograma');
            $table->integer('id_tarefa')->unsigned();
            $table->foreign('id_tarefa')->references('id')->on('tbTarefas')->onDelete('cascade');
            $table->date('data');
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
        Schema::dropIfExists('tb_cronogramas');
    }
}
