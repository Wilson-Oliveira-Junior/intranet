<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueCampoNomeTableTbDatasComemorativas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbDatasComemorativas', function (Blueprint $table) {
            //
            $table->unique('nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbDatasComemorativas', function (Blueprint $table) {
            $table->dropUnique('nome_unique');
        });
    }
}
