<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id(); // id INT AUTO_INCREMENT PRIMARY KEY NOT NULL
            $table->string('nombre', 120); // nombre VARCHAR(120) NOT NULL
            $table->string('horario', 150)->nullable(); // horario TEXT(1000), nullable
            $table->string('direccion', 255)->nullable(); // Una sola dirección
            $table->string('telefono', 50)->nullable(); // telefono VARCHAR(9), nullable
            $table->string('persona_contacto', 120)->nullable(); // persona_contacto VARCHAR(120), nullable
            $table->string('telefono_persona_contacto', 50)->nullable(); // telefono_persona_contacto VARCHAR(9), nullable
            $table->text('nota_cliente')->nullable(); // nota_cliente TEXT(1000), nullable
            $table->string('gps', 255)->nullable(); // GPS VARCHAR(255), nullable
            $table->timestamps(); // Agrega created_at y updated_at
            $table->softDeletes(); // Agrega deleted_at para SoftDeletes
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
