<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClienteBasculasController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\BasculasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ArchivoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PapeleraController;

use Illuminate\Support\Facades\Hash;




Route::controller(LoginController::class)->group(function () {
    Route::get('login', 'showLoginForm')->name('login');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->name('logout');
});

// Todas las rutas que requieran autenticación
Route::middleware(['auth'])->group(function () {
    // Ruta raíz '/', redirige a '/home'
    Route::get('/', function () {
        return redirect('/lista-clientes');
    });

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile', [UserController::class, 'updatePassword'])->name('profile.updatePassword');

    Route::get('/basculas-cliente/{id}', [BasculasController::class, 'getBasculasByClientId'])->name('basculas-cliente');

    Route::get('/infoCliente/{id}', [ClientesController::class, 'infoCliente'])->name('info-cliente');
    Route::get('/infoBascula/{id}', [BasculasController::class, 'infoBascula'])->name('info-bascula');

    //Route::get('/lista-clientes', [ClientesController::class, 'getClientes'])->name('lista-clientes');
    Route::get('/lista-clientes', [ClientesController::class, 'getClientesRecientes'])->name('lista-clientes');
    Route::get('/lista-basculas', [BasculasController::class, 'getBasculasRecientes'])->name('lista-basculas');

    Route::put('/editar-cliente/{id}', [ClientesController::class, 'editarCliente'])->name('editarCliente');
    Route::put('/editar-bascula/{id}', [BasculasController::class, 'editarBascula'])->name('editarBascula');

    Route::post('/basculas/{id}/dar-de-baja', [BasculasController::class, 'darDeBajaBascula'])->name('darDeBajaBascula');
    Route::post('/basculas/{id}/dar-de-alta', [BasculasController::class, 'darDeAltaBascula'])->name('darDeAltaBascula');
    Route::post('/actualizar-datos-completos/{id}', [BasculasController::class, 'actualizarDatosCompletos'])->name('actualizar-datos-completos');


    Route::get('/lista-clientes/buscar', [ClientesController::class, 'buscarClientes'])->name('buscarClientes');
    Route::get('/lista-basculas/buscar', [BasculasController::class, 'buscarBasculas'])->name('buscarBasculas');

    Route::get('/buscar-clientes-modal', [ClientesController::class, 'buscarClientesModal'])->name('buscarClientesModal');
    Route::post('/mover-bascula', [BasculasController::class, 'moverBascula'])->name('moverBascula');


    Route::get('/api/clientes/{id}/basculas', [BasculasController::class, 'getBasculasList'])->name('api.clientes.basculas');

    Route::get('/crear-cliente', [ClientesController::class, 'crearClienteView'])->name('crear-cliente');
    Route::post('/crear-cliente', [ClientesController::class, 'crearCliente'])->name('crearCliente');

    //Route::get('/crear-bascula', [BasculasController::class, 'crearBasculaView'])->name('crear-bascula');
    Route::post('/crear-bascula', [BasculasController::class, 'crearBascula'])->name('basculas.store');
    Route::get('/crear-bascula/{idCliente}', [BasculasController::class, 'crearBasculaView'])->name('crear-bascula');

    Route::post('/imagenes/agregar', [GaleriaController::class, 'agregarImagen'])->name('imagenes.agregar');

    Route::post('/videos/agregar', [VideoController::class, 'agregarVideo'])->name('videos.agregar');
    Route::post('/videos/editar/{id}', [VideoController::class, 'editarNombreVideo'])->name('videos.editarNombre');


    Route::post('/archivos/agregar', [ArchivoController::class, 'agregarArchivo'])->name('archivos.agregar');
    Route::get('/archivos/{tipo}/{id}', [ArchivoController::class, 'mostrarArchivos'])->name('archivos.mostrar');
    Route::post('/archivos/editar/{id}', [ArchivoController::class, 'editarNombreArchivo'])->name('archivos.editarNombre');


    Route::middleware(['auth', 'can:accessAdmin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'home'])->name('admin.home');
        Route::get('/admin/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/admin/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [UserController::class, 'delete'])->name('admin.users.delete');
        Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');


    });

    Route::middleware(['auth', 'can:accessModeratorAndAdmin'])->group(function () {

        Route::delete('/archivos/eliminar/{id}', [ArchivoController::class, 'eliminarArchivo'])->name('archivos.eliminar');
        Route::delete('/imagenes/eliminar/{id}', [GaleriaController::class, 'eliminarImagen'])->name('imagenes.eliminar');
        Route::delete('/videos/eliminar/{id}', [VideoController::class, 'eliminarVideo'])->name('videos.eliminar');


        Route::get('/papelera/clientes', [PapeleraController::class, 'clientesPapelera'])->name('admin.clientes-papelera');
        Route::get('/papelera/basculas', [PapeleraController::class, 'basculasPapelera'])->name('admin.basculas-papelera');

        Route::post('papelera/cliente/{id}', [PapeleraController::class, 'moverClientePapelera'])->name('papelera.cliente.mover');
        Route::post('papelera/bascula/{id}', [PapeleraController::class, 'moverBasculaPapelera'])->name('papelera.bascula.mover');

        Route::post('/recuperar-cliente/{id}', [PapeleraController::class, 'recuperarCliente'])->name('recuperar-cliente');
        Route::post('/recuperar-bascula/{id}', [PapeleraController::class, 'recuperarBascula'])->name('recuperar-bascula');

        Route::get('/buscar-clientes-eliminados', [PapeleraController::class, 'buscarClientesEliminados'])->name('buscarClientesEliminados');
        Route::get('/buscar-basculas-eliminadas', [PapeleraController::class, 'buscarBasculasEliminadas'])->name('buscarBasculasEliminadas');
        Route::delete('/papelera/clientes/vaciar', [PapeleraController::class, 'vaciarPapeleraClientes'])->name('vaciar-papelera-clientes');
        Route::delete('/papelera/basculas/vaciar', [PapeleraController::class, 'vaciarPapeleraBasculas'])->name('vaciar-papelera-basculas');


        Route::delete('/admin/clientes/{id}', [ClientesController::class, 'destroy'])->name('admin.clientes.destroy');
        Route::delete('/admin/basculas/{id}', [BasculasController::class, 'destroy'])->name('admin.basculas.destroy');
    });
});
