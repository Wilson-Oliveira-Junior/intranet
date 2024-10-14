<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGatilhoAdiamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_gatilho_adiamento', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_gatilho');
            $table->string('id_projeto');
            $table->string('id_usuario');
            $table->string('data_adiamento');
            $table->string('motivo');
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
        Schema::dropIfExists('tb_gatilho_adiamento');
    }
}
