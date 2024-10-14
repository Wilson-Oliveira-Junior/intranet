<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTbContaAzulVendas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbContaAzulVendas', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('idcliente')->unsigned();
            $table->string('idVenda', 64);
            $table->string('idContaAzul', 64);
            $table->integer('numero_venda');
            $table->date('emissao');
            $table->decimal('valor', 8, 2);
            $table->integer('status')->comment('0-Pago, 1-Pendente, 2- Atrasado');
            $table->date('vencimento');
            $table->timestamps();
            $table->foreign('idcliente')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbContaAzulVendas');
    }
}
