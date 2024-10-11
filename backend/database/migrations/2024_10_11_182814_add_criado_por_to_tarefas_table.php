<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->string('criado_por')->nullable(); // Adiciona o campo criado_por
        });
    }

    public function down()
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropColumn('criado_por');
        });
    }

};
