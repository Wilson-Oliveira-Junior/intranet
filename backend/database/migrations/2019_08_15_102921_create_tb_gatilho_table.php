<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTbGatilhoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_gatilhos', function (Blueprint $table) {
            $table->increments('id');

            // Id do Tipo de Projeto
            $table->integer('id_tipo_projeto');

            // Id do gatilho utilizado
            $table->integer('id_gatilho_template');

            // Id do gatilho manual
            $table->integer('id_gatilho_template_manual');

            // Status do Gatilho
            $table->enum('status',['Aberto','Finalizado'])->default('Aberto');
            
            // Verificando qual usuário que concluiu o gatilho
            $table->integer('id_usuario');

            // Data de conclusão do gatilho
            $table->string('data_conclusao');

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
        Schema::dropIfExists('tb_gatilho');
    }
}
