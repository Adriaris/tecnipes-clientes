<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Bascula;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PapeleraController extends Controller
{
    public function clientesPapelera()
    {
        session(['titulo' => 'Clientes Eliminados']);

        // Obtener los clientes eliminados utilizando SoftDeletes
        $clientesEliminados = Cliente::onlyTrashed()->paginate(12);

        // Devolver la vista con los datos de los clientes eliminados
        return view('admin.clientes-papelera', compact('clientesEliminados'));
    }

    public function basculasPapelera()
    {
        session(['titulo' => 'Básculas Eliminadas']);

        // Obtener las básculas eliminadas utilizando SoftDeletes y cargar también los clientes eliminados
        $basculasEliminadas = Bascula::onlyTrashed()
            ->with([
                'cliente' => function ($query) {
                    $query->withTrashed(); // Incluye los clientes eliminados
                }
            ])
            ->paginate(12);

        // Devolver la vista con los datos de las básculas eliminadas
        return view('admin.basculas-papelera', compact('basculasEliminadas'));
    }


    public function moverClientePapelera($id)
    {
        // Encontrar el cliente por su ID
        $cliente = Cliente::findOrFail($id);

        // Mover el cliente a la papelera (SoftDelete)
        $cliente->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->action([ClientesController::class, 'getClientesRecientes'])->with('success', 'Cliente eliminado con éxito.');
    }

    public function moverBasculaPapelera($id)
    {
        // Encontrar la báscula por su ID
        $bascula = Bascula::findOrFail($id);

        // Mover la báscula a la papelera (SoftDelete)
        $bascula->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->action([BasculasController::class, 'getBasculasRecientes'])->with('success', 'Báscula eliminada con éxito.');
    }

    public function recuperarCliente(Request $request, $id)
    {
        // Iniciar una transacción para asegurar la atomicidad de la operación
        DB::beginTransaction();

        try {
            // Encuentra el cliente eliminado junto con sus básculas eliminadas
            $cliente = Cliente::withTrashed()->with([
                'basculas' => function ($query) {
                    $query->withTrashed();
                }
            ])->findOrFail($id);

            // Recupera el cliente
            $cliente->restore();

            // Verifica si el usuario ha elegido recuperar las básculas también
            if ($request->input('recuperar') === 'cliente_y_basculas') {
                // Recupera todas las básculas asociadas al cliente
                $cliente->basculas()->withTrashed()->restore();
            }

            // Confirmar la transacción
            DB::commit();

            return redirect()->route('info-cliente', ['id' => $cliente->id])->with('success', 'Cliente restaurado exitosamente');

        } catch (\Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();

            return redirect()->back()->with('error', 'Ocurrió un error al intentar recuperar el cliente y sus básculas.');
        }
    }


    public function recuperarBascula($id)
    {
        // Iniciar una transacción para asegurar que ambas restauraciones se completen o ninguna
        DB::beginTransaction();

        try {
            // Encuentra la báscula eliminada junto con el cliente (incluyendo eliminados)
            $bascula = Bascula::withTrashed()->with([
                'cliente' => function ($query) {
                    $query->withTrashed();
                }
            ])->findOrFail($id);

            // Verifica si el cliente está eliminado antes de intentar restaurarlo
            if ($bascula->cliente && $bascula->cliente->trashed()) {
                // Recupera el cliente primero
                $bascula->cliente->restore();
            }

            // Recupera la báscula
            $bascula->restore();

            // Confirmar la transacción
            DB::commit();

            // Redirige de vuelta con un mensaje de éxito
            return redirect()->route('info-bascula', ['id' => $bascula->id])->with('success', 'Báscula restaurada con éxito.');

        } catch (\Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();

            // Redirigir de vuelta con un mensaje de error
            return redirect()->back()->with('error', 'Ocurrió un error al intentar recuperar la báscula y su cliente.');
        }
    }

    public function buscarClientesEliminados(Request $request)
    {
        $busqueda = $request->input('busqueda');

        // Buscar solo clientes eliminados por nombre o teléfono
        $clientesEliminados = Cliente::onlyTrashed()
            ->where(function ($query) use ($busqueda) {
                $query->where('nombre', 'LIKE', "%$busqueda%")
                    ->orWhere('telefono', 'LIKE', "%$busqueda%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Retornar la vista con los resultados de búsqueda
        return view('admin.clientes-papelera', compact('clientesEliminados'));
    }



    public function buscarBasculasEliminadas(Request $request)
    {
        $busqueda = $request->input('busqueda');

        // Buscar solo básculas eliminadas por cliente, número de serie, código o modelo
        $basculasEliminadas = Bascula::onlyTrashed()
            ->where(function ($query) use ($busqueda) {
                $query->whereHas('cliente', function ($query) use ($busqueda) {
                    $query->withTrashed()->where('nombre', 'LIKE', "%$busqueda%");
                })
                    ->orWhere('numero_serie', 'LIKE', "%$busqueda%")
                    ->orWhere('codigo', 'LIKE', "%$busqueda%")
                    ->orWhere('modelo', 'LIKE', "%$busqueda%");
            })
            ->with([
                'cliente' => function ($query) {
                    $query->withTrashed(); // Asegura que el cliente eliminado se cargue también
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Retornar la vista con los resultados de búsqueda
        return view('admin.basculas-papelera', compact('basculasEliminadas'));
    }

    public function vaciarPapeleraClientes()
    {
        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->route('lista-clientes')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        DB::transaction(function () {
            $clientesEliminados = Cliente::onlyTrashed()->get();

            foreach ($clientesEliminados as $cliente) {
                // Eliminar todos los archivos asociados al cliente
                foreach ($cliente->archivos as $archivo) {
                    Storage::disk('public')->delete($archivo->url_archivo);
                    $archivo->forceDelete();
                }

                // Eliminar todas las imágenes asociadas al cliente
                foreach ($cliente->imagenes as $imagen) {
                    Storage::disk('public')->delete($imagen->url_imagen);
                    Storage::disk('public')->delete($imagen->thumbnail_url);
                    $imagen->forceDelete();
                }

                // Eliminar todos los vídeos asociados al cliente
                foreach ($cliente->videos as $video) {
                    Storage::disk('public')->delete($video->url_video);
                    $video->forceDelete();
                }

                // Eliminar todas las básculas asociadas al cliente y sus archivos, imágenes y vídeos
                foreach ($cliente->basculas as $bascula) {
                    // Eliminar archivos asociados a la báscula
                    foreach ($bascula->archivos as $archivo) {
                        Storage::disk('public')->delete($archivo->url_archivo);
                        $archivo->forceDelete();
                    }

                    // Eliminar imágenes asociadas a la báscula
                    foreach ($bascula->imagenes as $imagen) {
                        Storage::disk('public')->delete($imagen->url_imagen);
                        Storage::disk('public')->delete($imagen->thumbnail_url);
                        $imagen->forceDelete();
                    }

                    // Eliminar vídeos asociados a la báscula
                    foreach ($bascula->videos as $video) {
                        Storage::disk('public')->delete($video->url_video);
                        $video->forceDelete();
                    }

                    // Eliminar la báscula permanentemente
                    $bascula->forceDelete();
                }

                // Finalmente, eliminar el cliente permanentemente
                $cliente->forceDelete();
            }
        });

        return redirect()->route('admin.clientes-papelera')->with('success', 'Todos los clientes eliminados han sido borrados permanentemente.');
    }

    public function vaciarPapeleraBasculas()
    {
        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->route('lista-basculas')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        DB::transaction(function () {
            $basculasEliminadas = Bascula::onlyTrashed()->get();

            foreach ($basculasEliminadas as $bascula) {
                // Eliminar archivos asociados a la báscula
                foreach ($bascula->archivos as $archivo) {
                    Storage::disk('public')->delete($archivo->url_archivo);
                    $archivo->forceDelete();
                }

                // Eliminar imágenes asociadas a la báscula
                foreach ($bascula->imagenes as $imagen) {
                    Storage::disk('public')->delete($imagen->url_imagen);
                    Storage::disk('public')->delete($imagen->thumbnail_url);
                    $imagen->forceDelete();
                }

                // Eliminar vídeos asociados a la báscula
                foreach ($bascula->videos as $video) {
                    Storage::disk('public')->delete($video->url_video);
                    $video->forceDelete();
                }

                // Eliminar la báscula permanentemente
                $bascula->forceDelete();
            }
        });

        return redirect()->route('admin.basculas-papelera')->with('success', 'Todas las básculas eliminadas han sido borradas permanentemente.');
    }














}

