<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basculas_recientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bascula_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            // Definir claves foráneas
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('bascula_id')->references('id')->on('basculas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basculas_recientes');
    }
};
