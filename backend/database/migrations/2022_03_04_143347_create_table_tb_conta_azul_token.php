<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTbContaAzulToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbContaAzulToken', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token', 164);
            $table->string('refresh_token', 164);
            $table->string('expired_in', 164);
            $table->string('expirou', 32);
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
        Schema::dropIfExists('tbContaAzulToken');
    }
}
