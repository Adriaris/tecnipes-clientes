<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(); // Campo para almacenar el nombre del video
            $table->string('url_video'); // URL del archivo de video
            $table->unsignedBigInteger('videoable_id'); // Polimorfismo: ID del modelo relacionado
            $table->string('videoable_type'); // Polimorfismo: Tipo del modelo relacionado
            $table->timestamps(); // Campos de timestamp
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
