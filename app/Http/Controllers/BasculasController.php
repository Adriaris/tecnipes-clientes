<?php

namespace App\Http\Controllers;

use App\Models\Bascula;
use App\Models\BasculaReciente;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BasculasController extends Controller
{


    public function getBasculasRecientes()
    {
        // Obtén el ID del usuario actual
        $userId = Auth::id();

        // Consulta la tabla "basculas_recientes" para obtener los registros de basculas visitadas por el usuario actual
        $basculasVisitadas = BasculaReciente::where('usuario_id', $userId)
            ->orderBy('created_at', 'desc')
            ->pluck('bascula_id')->toArray(); // Obtén los IDs de las basculas visitadas

        // Consulta la tabla "basculas" para obtener los datos completos de las basculas correspondientes, excluyendo las eliminadas
        $basculas = Bascula::whereIn('id', $basculasVisitadas)
            ->paginate(12); // Se aplica SoftDeletes automáticamente

        // Establece el título de la sesión
        session(['titulo' => 'Básculas recientes']);

        // Retorna la vista con las basculas recientes paginadas
        $showCreateButton = false;
        return view('lista-basculas', compact('basculas', 'showCreateButton'));
    }


    public function infoBascula($id)
    {

        // Recupera el cliente por ID
        $bascula = Bascula::find($id);

        // Verifica si se encontró el cliente
        if (!$bascula) {
            // Manejo de error si el cliente no se encuentra
            // Puedes redirigir a una página de error o realizar alguna acción adecuada
            return redirect()->route('lista-basculas')->with('error', 'Bascula no encontrado.');
        }
        // Establece el título de la sesión
        session(['titulo' => 'Info Bascula']);

        // Registra la visita del usuario al cliente
        $this->registrarVisitaBascula($bascula);

        return view('info-bascula', ['bascula' => $bascula]);
    }

    private function registrarVisitaBascula($bascula)
    {
        // Obtiene el ID del usuario autenticado
        $usuarioId = Auth::id();

        // Verifica si el usuario ha visitado esta báscula antes
        $basculaVisitada = BasculaReciente::where('bascula_id', $bascula->id)
            ->where('usuario_id', $usuarioId)
            ->first();

        if (!$basculaVisitada) {
            // Si el usuario no ha visitado esta báscula antes, crea un registro en la tabla
            BasculaReciente::create([
                'bascula_id' => $bascula->id,
                'usuario_id' => $usuarioId,
                'fecha_visita' => now(),
            ]);
        } else {
            // Si el usuario ya ha visitado esta báscula, actualiza la fecha de visita
            $basculaVisitada->update(['fecha_visita' => now()]);
        }
    }


    public function getBasculasByClientId($id)
    {

        $cliente = Cliente::find($id);
        if (!$cliente) {
            return redirect()->route('lista-clientes')->with('error', 'Cliente no encontrado.');
        }
        session(['titulo' => $cliente->nombre]);

        // Recupera las básculas del cliente por ID
        $basculas = Bascula::where('id_cliente', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Retorna la vista con las básculas paginadas
        $showCreateButton = true;
        return view('lista-basculas', compact('basculas', 'showCreateButton', 'id'));
    }

    public function getBasculasList($clientId)
    {
        $basculas = Bascula::where('id_cliente', $clientId)
            ->select('id', 'fabricante', 'modelo', 'numero_serie', 'created_at') // Asegúrate de seleccionar la columna created_at
            ->orderBy('created_at', 'desc') // Ordenar por fecha de creación en orden descendente
            ->get();
        return response()->json($basculas);
    }



    public function buscarBasculas(Request $request)
    {
        $nombre = $request->input('busqueda');

        // Realiza la consulta para buscar basculas por cliente, número de serie o código
        $basculas = Bascula::whereHas('cliente', function ($query) use ($nombre) {
            $query->where('nombre', 'LIKE', "%$nombre%");
        })
            ->orWhere('numero_serie', 'LIKE', "%$nombre%")
            ->orWhere('codigo', 'LIKE', "%$nombre%")
            ->orWhere('modelo', 'LIKE', "%$nombre%")
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();
        ;

        // Establece el título de la sesión
        session(['titulo' => 'Resultados de Búsqueda']);

        // Retorna la vista con los resultados de la búsqueda
        return view('lista-basculas', ['basculas' => $basculas]);
    }



    public function editarBascula(Request $request, $id)
    {
        // Validación de los datos ingresados en el formulario
        $request->validate([
            'instrumento' => 'required|max:100',
            'indicador' => 'required|max:30',
            'fabricante' => 'required|max:100',
            'modelo' => 'required|max:30',
            'numero_serie' => 'required|max:100',
            'codigo' => 'required|max:50',
            'ubicacion' => 'required|max:100',
            'maximo' => 'required|max:30',
            'unidad_medida_kg_g' => 'required|max:2',
            'escalon' => 'required|max:30',
            'division' => 'required|max:20',
            'acabado' => 'required|max:30',
            'instalacion' => 'required|max:30',
            'dimensiones' => 'required|max:30',
            'numero_apoyos' => 'required|integer',
            'tipo_apoyo' => 'required|max:50',
            'modelo_celula' => 'required|max:100',
            'cap_celula' => 'required|max:30',
            'nota_bascula' => 'nullable|string|max:3000'
        ]);

        // Buscar la báscula existente
        $bascula = Bascula::findOrFail($id);

        // Actualización de la báscula con los datos validados
        $bascula->update([
            'instrumento' => $request->instrumento === 'Otro' ? $request->otroInstrumento : $request->instrumento,
            'indicador' => $request->indicador === 'Otro' ? $request->otroIndicador : $request->indicador,
            'fabricante' => $request->fabricante,
            'modelo' => $request->modelo,
            'numero_serie' => $request->numero_serie,
            'codigo' => $request->codigo,
            'ubicacion' => $request->ubicacion,
            'maximo' => $request->maximo,
            'unidad_medida_kg_g' => $request->unidad_medida_kg_g,
            'escalon' => $request->escalon,
            'division' => $request->division,
            'acabado' => $request->acabado === 'Otro' ? $request->otroAcabado : $request->acabado,
            'instalacion' => $request->instalacion === 'Otro' ? $request->otraInstalacion : $request->instalacion,
            'dimensiones' => $request->dimensiones,
            'numero_apoyos' => $request->numero_apoyos,
            'tipo_apoyo' => $request->tipo_apoyo === 'Otro' ? $request->otroTipoApoyo : $request->tipo_apoyo,
            'modelo_celula' => $request->modelo_celula,
            'cap_celula' => $request->cap_celula,
            'nota_bascula' => $request->nota_bascula
        ]);

        // Redirección al usuario con un mensaje de éxito
        return redirect()->route('info-bascula', ['id' => $id])->with('success', 'Báscula actualizada con éxito.');
    }





    public function crearBasculaView($idCliente)
    {
        session(['titulo' => 'Nueva bascula...']);
        return view('crear-bascula', compact('idCliente')); // Asume que la vista se llama 'crear.blade.php' y está bajo una carpeta 'basculas'
    }


    public function crearBascula(Request $request)
    {
        // Validación de los datos ingresados en el formulario
        $validatedData = $request->validate([
            'instrumento' => 'required|max:100',
            'indicador' => 'required|max:30',
            'fabricante' => 'required|max:100',
            'modelo' => 'required|max:30',
            'numero_serie' => 'required|max:100',
            'codigo' => 'required|max:50',
            'ubicacion' => 'required|max:100',
            'maximo' => 'required|max:30',
            'unidad_medida_kg_g' => 'required|max:2',
            'escalon' => 'required|max:30',
            'division' => 'required|max:30',
            'acabado' => 'required|max:30',
            'instalacion' => 'required|max:30',
            'dimensiones' => 'required|max:30',
            'numero_apoyos' => 'required|integer',
            'tipo_apoyo' => 'required|max:30',
            'modelo_celula' => 'required|max:100',
            'cap_celula' => 'required|max:30',
            'nota_bascula' => 'nullable|string|max:3000',
            'datos_completos' => 'required|boolean',
            'id_cliente' => 'required|integer|exists:clientes,id',
        ]);

        // Ajustar los valores "Otro" para los campos seleccionables
        $camposOtro = [
            'tipo_apoyo' => 'otroTipoApoyo',
            'acabado' => 'otroAcabado',
            'instalacion' => 'otraInstalacion',
            'instrumento' => 'otroInstrumento',
            'indicador' => 'otroIndicador'
        ];

        foreach ($camposOtro as $campo => $otroCampo) {
            if ($request->input($campo) === 'Otro' && $request->filled($otroCampo)) {
                $validatedData[$campo] = $request->input($otroCampo);
            }
        }

        // Añadir el campo 'operativa' con valor true por defecto
        $validatedData['operativa'] = true;


        // Creación de la báscula con los datos validados y obtener la instancia creada
        $bascula = Bascula::create($validatedData);

        // Redirección al usuario con un mensaje de éxito
        return redirect()->route('info-bascula', ['id' => $bascula->id])->with('success', 'Báscula creada con éxito.');

    }

    public function moverBascula(Request $request)
    {
        $validatedData = $request->validate([
            'bascula_id' => 'required|integer|exists:basculas,id',
            'cliente_id' => 'required|integer|exists:clientes,id'
        ]);

        $bascula = Bascula::findOrFail($validatedData['bascula_id']);
        $bascula->id_cliente = $validatedData['cliente_id'];
        $bascula->save();

        return response()->json(['success' => true]);
    }



    public function darDeBajaBascula($id)
    {
        $bascula = Bascula::findOrFail($id);

        $bascula->operativa = false;
        $bascula->save();

        return redirect()->route('info-bascula', ['id' => $id])->with('success', 'Báscula dada de baja con éxito.');
    }

    public function darDeAltaBascula($id)
    {
        $bascula = Bascula::findOrFail($id);


        $bascula->operativa = true;
        $bascula->save();

        return redirect()->route('info-bascula', ['id' => $id])->with('success', 'Báscula dada de alta con éxito.');
    }

    public function actualizarDatosCompletos(Request $request, $id)
    {
        try {
            $bascula = Bascula::findOrFail($id);
            $bascula->datos_completos = !$bascula->datos_completos; // Toggle the value
            $bascula->save();

            return redirect()->back()->with('success', 'Estado de los datos completados actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el estado de los datos completados.');
        }
    }


    public function destroy($id)
    {
        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->route('lista-basculas')->with('error', 'No tienes permiso para realizar esta acción.');
        }

        DB::transaction(function () use ($id) {
            $bascula = Bascula::withTrashed()->findOrFail($id); // Usar withTrashed() para incluir basculas eliminadas

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

            // Finalmente, eliminar la báscula permanentemente
            $bascula->forceDelete();
        });

        return redirect()->action([BasculasController::class, 'getBasculasRecientes'])->with('success', 'Báscula eliminada con éxito.');
    }





}
