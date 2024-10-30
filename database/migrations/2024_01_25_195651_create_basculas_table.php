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
        Schema::create('basculas', function (Blueprint $table) {
            $table->id();
            $table->string('instrumento', 100)->nullable();
            $table->string('indicador', 30)->nullable();
            $table->string('fabricante', 100)->nullable();
            $table->string('modelo', 30)->nullable();
            $table->string('numero_serie', 100)->nullable();
            $table->string('codigo', 50)->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->string('maximo', 30)->nullable();
            $table->string('unidad_medida_kg_g', 2)->nullable();
            $table->string('escalon', 30)->nullable();
            $table->string('division', 30)->nullable(); // Campo 'division' como decimal con 6 dígitos decimales
            $table->integer('divisiones')->nullable(); // Campo 'divisiones' añadido como entero
            $table->string('acabado', 30)->nullable();
            $table->string('instalacion', 30)->nullable();
            $table->string('dimensiones', 30)->nullable();
            $table->integer('numero_apoyos')->nullable();
            $table->string('tipo_apoyo', 50)->nullable();
            $table->string('modelo_celula', 100)->nullable();
            $table->string('cap_celula', 30)->nullable();
            $table->text('nota_bascula')->nullable();
            $table->boolean('operativa')->default(true);
            $table->boolean('datos_completos')->default(false);
            $table->foreignId('id_cliente')->constrained('clientes')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('basculas');
    }
};
