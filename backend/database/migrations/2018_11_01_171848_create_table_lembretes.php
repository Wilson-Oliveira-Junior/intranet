<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLembretes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lembretes', function (Blueprint $table) {
            $table->increments('id');
            
            // Definindo o Relacionamento com o setor ID
            $table->integer('setor_id')->unsigned();
            $table->foreign('setor_id')->references('id')->on('setor_usuarios');

            // Definindo o Relacionamento com o usuario ID
            $table->integer('usuario_id')->unsigned();
            $table->foreign('usuario_id')->references('id')->on('users');

            // Definindo o Relacionamento com o cliente ID
            $table->integer('cliente_id')->unsigned();
            $table->foreign('cliente_id')->references('id')->on('clientes');

            // Campos Normais
            $table->date('data');
            $table->string('hora');
            $table->enum('notificar',['Notificação','E-mail'])->default('Notificação');
            $table->enum('importancia',['Alta','Média', 'Baixa'])->default('Baixa');
            $table->enum('concluido',['S','N'])->default('N');
            $table->string('postou_id');
            $table->string('email');
            $table->string('titulo');
            $table->string('mensagem');
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
