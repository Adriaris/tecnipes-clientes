<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\ClienteReciente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ClientesController extends Controller
{

    public function getClientesRecientes()
    {
        // Obtener el ID del usuario actual
        $userId = Auth::id();

        // Consultar la tabla clientes_recientes para obtener los registros de clientes recientes y sus nombres
        $clientes = DB::table('clientes_recientes AS cr')
            ->join('clientes AS c', 'cr.cliente_id', '=', 'c.id')
            ->select('c.id', 'c.nombre', 'c.direccion', 'c.telefono', 'cr.fecha_visita')
            ->where('cr.usuario_id', $userId)
            ->whereNull('c.deleted_at')  // Excluir clientes que están en la papelera
            ->orderBy('cr.fecha_visita', 'desc')
            ->paginate(12); // Aquí puedes ajustar el número de resultados por página

        // Establecer el título de la sesión
        session(['titulo' => 'Clientes recientes']);

        // Retornar la vista con los clientes recientes paginados y los datos
        return view('lista-clientes', ['clientes' => $clientes]);
    }



    private function registrarVisitaCliente($cliente)
    {
        // Obtiene el ID del usuario autenticado
        $usuarioId = Auth::id();

        // Verifica si el usuario ha visitado este cliente antes
        $clienteVisitado = ClienteReciente::where('cliente_id', $cliente->id)
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$clienteVisitado) {
            // Si el usuario no ha visitado este cliente antes, crea un registro en la tabla
            ClienteReciente::create([
                'cliente_id' => $cliente->id,
                'usuario_id' => $usuarioId,
                'fecha_visita' => now(),
            ]);
        } else {
            // Si el usuario ya ha visitado este cliente, actualiza la fecha de visita
            $clienteVisitado->update(['fecha_visita' => now()]);
        }
    }


    public function infoCliente($id)
    {
        // Recupera el cliente por ID con sus imágenes relacionadas
        $cliente = Cliente::with('imagenes')->find($id);

        // Verifica si se encontró el cliente
        if (!$cliente) {
            // Manejo de error si el cliente no se encuentra
            // Puedes redirigir a una página de error o realizar alguna acción adecuada
            return redirect()->route('lista-clientes')->with('error', 'Cliente no encontrado.');
        }

        // Establece el título de la sesión
        session(['titulo' => $cliente->nombre]);

        // Registra la visita del usuario al cliente
        $this->registrarVisitaCliente($cliente);

        // Retorna la vista con la información del cliente
        return view('info-cliente', ['cliente' => $cliente]);
    }


    public function editarCliente(Request $request, $id)
    {
        try {

            // Valida los campos que deseas actualizar
            $request->validate([
                'nombre' => 'required|string|max:120',
                'horario' => 'nullable|string|max:150',
                'direccion' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:50',
                'persona_contacto' => 'nullable|string|max:120',
                'telefono_persona_contacto' => 'nullable|string|max:50',
                'gps' => 'nullable|string|max:255',
                'nota_cliente' => 'nullable|string|max:3000',
            ]);

            // Encuentra el cliente por su ID
            $cliente = Cliente::findOrFail($id);

            // Actualiza el cliente con los datos proporcionados
            $cliente->update([
                'nombre' => $request->input('nombre'),
                'horario' => $request->input('horario'),
                'direccion' => $request->input('direccion'),
                'telefono' => $request->input('telefono'),
                'persona_contacto' => $request->input('persona_contacto'),
                'telefono_persona_contacto' => $request->input('telefono_persona_contacto'),
                'gps' => $request->input('gps'),
                'nota_cliente' => $request->input('nota_cliente'),
            ]);

            // Redirige a la página de información del cliente con un mensaje de éxito
            return redirect()->route('info-cliente', ['id' => $id])->with('success', 'Cliente actualizado correctamente');

        } catch (\Exception $e) {
            // En caso de error, muestra un mensaje de error y redirige de nuevo al formulario de edición
            return Redirect::back()->withInput()->withErrors(['error' => 'Hubo un problema al actualizar el cliente. Por favor, intenta nuevamente.']);
        }
    }





    public function obtenerClientes($busqueda)
    {
        // Realiza la consulta para buscar clientes por nombre o teléfono
        $clientes = Cliente::where('nombre', 'LIKE', "%$busqueda%")
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return $clientes;
    }
    public function buscarClientes(Request $request)
    {
        $busqueda = $request->input('busqueda');

        // Usa la función obtenerClientes para realizar la búsqueda
        $clientes = $this->obtenerClientes($busqueda);

        // Establece el título de la sesión
        session(['titulo' => 'Resultados de Búsqueda']);

        // Retorna la vista con los resultados de la búsqueda
        return view('lista-clientes', ['clientes' => $clientes]);
    }

    public function buscarClientesModal(Request $request)
    {
        $busqueda = $request->input('query', '');
        $clientes = Cliente::where('nombre', 'LIKE', "%$busqueda%")
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return response()->json([
            'clientes' => view('partials.clientes-lista', ['clientes' => $clientes])->render(),
            'pagination' => (string) $clientes->links(),
        ]);
    }



    public function crearClienteView()
    {
        session(['titulo' => 'Nuevo cliente...']);
        return view('crear-cliente');
    }


    public function crearCliente(Request $request)
    {
        try {
            // Valida los datos del formulario
            $request->validate([
                'nombre' => 'required|string|max:120',
                'horario' => 'nullable|string|max:150',
                'direccion' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:50',
                'persona_contacto' => 'nullable|string|max:120',
                'telefono_persona_contacto' => 'nullable|string|max:50',
                'gps' => 'nullable|string|max:255',
                'nota_cliente' => 'nullable|string|max:3000',
            ]);

            // Crea un nuevo cliente con los datos del formulario
            $cliente = new Cliente([
                'nombre' => $request->input('nombre'),
                'horario' => $request->input('horario'),
                'direccion' => $request->input('direccion'),
                'telefono' => $request->input('telefono'),
                'persona_contacto' => $request->input('persona_contacto'),
                'telefono_persona_contacto' => $request->input('telefono_persona_contacto'),
                'gps' => $request->input('gps'),
                'nota_cliente' => $request->input('nota_cliente'),
            ]);

            // Guarda el nuevo cliente en la base de datos
            $cliente->save();

            // Redirecciona a una vista de éxito o a donde desees después de la creación
            return redirect()->route('info-cliente', ['id' => $cliente->id])->with('success', 'Cliente creado exitosamente');


        } catch (\Exception $e) {
            // En caso de error, muestra un mensaje de error y redirige de nuevo al formulario de edición
            //return Redirect::back()->withInput()->withErrors(['error' => 'Hubo un problema al actualizar el cliente. Por favor, intenta nuevamente.']);
            return Redirect::back()->withInput()->withErrors(['error' => "$e"]);
        }
    }

    public function destroy($id)
    {
        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->route('lista-clientes')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        DB::transaction(function () use ($id) {
            $cliente = Cliente::withTrashed()->findOrFail($id); // Usar withTrashed() para incluir clientes eliminados

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
            foreach ($cliente->basculas()->withTrashed()->get() as $bascula) { // Incluir basculas eliminadas
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
        });

        return redirect()->action([ClientesController::class, 'getClientesRecientes'])->with('success', 'Cliente eliminado con éxito.');
    }






}
