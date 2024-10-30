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
        Schema::create('archivos', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
            $table->string('url_archivo', 255); // url_archivo VARCHAR(255)
            $table->string('nombre_original', 255); // nombre_original VARCHAR(255)
            $table->string('tipo_archivo', 255); // tipo_archivo VARCHAR(255)
            $table->string('tipo_amigable', 50); // tipo_amigable VARCHAR(50)
            $table->unsignedBigInteger('archivoable_id'); // ID del modelo relacionado
            $table->string('archivoable_type'); // Tipo del modelo relacionado (Cliente o Báscula)
            $table->timestamps();

            $table->index(['archivoable_id', 'archivoable_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archivos');
    }
};
