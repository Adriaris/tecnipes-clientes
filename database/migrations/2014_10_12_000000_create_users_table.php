<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 255)->unique(); // Nombre de usuario único
            $table->string('email', 255)->unique(); // Correo electrónico único
            $table->timestamp('email_verified_at')->nullable(); // Opcional, para verificación de correo electrónico
            $table->string('password', 255);
            $table->string('api_token', 80)->unique()->nullable()->default(null); // Campo para el token de API
            $table->rememberToken(); // Token de recordar sesión
            $table->string('rol', 255)->default('normal'); // Agregar campo "rol" con valor por defecto "normal"
            $table->timestamps(); // Campos created_at y updated_at
        });

        // Insertar usuarios
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('1234'),
                'rol' => 'admin'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
