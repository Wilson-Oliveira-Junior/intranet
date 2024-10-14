<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableTbContaAzulVendasNumeroParcela extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbContaAzulVendas', function (Blueprint $table) {
            $table->integer('numero_parcela')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbContaAzulVendas', function (Blueprint $table) {
            $table->dropColumn('numero_parcela');
        });
    }
}
