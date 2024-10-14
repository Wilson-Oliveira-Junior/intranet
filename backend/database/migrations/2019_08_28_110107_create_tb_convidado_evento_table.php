<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbConvidadoEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_convidado_evento', function (Blueprint $table) {
            $table->increments('id');
            
            // ID do usuário que convidou
            $table->integer('id_usuario_postou');

            // ID do usuário convidado
            $table->integer('id_usuario_convidado');

            // ID do evento
            $table->integer('id_evento');

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
        Schema::dropIfExists('tb_convidado_evento');
    }
}
