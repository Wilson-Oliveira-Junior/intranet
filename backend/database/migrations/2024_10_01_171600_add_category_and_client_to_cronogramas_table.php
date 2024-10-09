<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cronogramas', function (Blueprint $table) {
            $table->string('categoria')->default('normal');
            $table->string('cliente')->default('Não Definido'); // Define um valor padrão
        });
    }

    public function down()
    {
        Schema::table('cronogramas', function (Blueprint $table) {
            $table->dropColumn('categoria');
            $table->dropColumn('cliente');
        });
    }
};
