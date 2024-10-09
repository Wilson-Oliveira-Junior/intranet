<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronogramasTable extends Migration
{
    public function up()
    {
        Schema::create('cronogramas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->date('data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cronogramas');
    }
}
