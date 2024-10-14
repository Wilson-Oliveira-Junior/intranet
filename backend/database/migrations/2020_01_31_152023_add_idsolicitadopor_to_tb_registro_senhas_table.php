<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdsolicitadoporToTbRegistroSenhasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbRegistroSenhas', function (Blueprint $table) {
            //adicionar loginPendente, urlPendente, senhaPendente, pendente
            $table->integer('idsolicitadopor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbRegistroSenhas', function (Blueprint $table) {
            //
            $table->dropColumn('idsolicitadopor');
        });
    }
}
