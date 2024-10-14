<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('times', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique(); // Nome do time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('times');
    }
};

// Atualizando a migração existente para usuários
Schema::table('users', function (Blueprint $table) {
    $table->foreignId('time_id')->nullable()->constrained('times')->onDelete('set null'); // Chave estrangeira para Times
});

// Atualizando a migração existente para tarefas
Schema::table('tarefas', function (Blueprint $table) {
    $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Chave estrangeira para Users
});
