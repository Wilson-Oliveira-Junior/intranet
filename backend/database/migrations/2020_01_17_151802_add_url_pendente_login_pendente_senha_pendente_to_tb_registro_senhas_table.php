<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUrlPendenteLoginPendenteSenhaPendenteToTbRegistroSenhasTable extends Migration
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
            $table->string('urlPendente',128)->nullable();
            $table->string('loginPendente',128)->nullable();
            $table->string('senhaPendente',64)->nullable();
            $table->boolean('pendente')->default(false);
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
            $table->dropColumn('urlPendente');
            $table->dropColumn('loginPendente');
            $table->dropColumn('senhaPendente');
            $table->dropColumn('pendente');
        });
    }
}
