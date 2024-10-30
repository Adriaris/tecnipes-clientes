<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Archivo;
use App\Models\Cliente;
use App\Models\Bascula;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class ArchivoController extends Controller
{
    public function agregarArchivo(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240', // Máximo 10MB
            'nombre_archivo' => 'required|string|max:255',
            'extension_archivo' => 'required|string|max:10',
            'tipo' => 'required|string',
            'idRelacion' => 'required|integer',
        ]);

        $tipo = $request->input('tipo');
        $idRelacion = $request->input('idRelacion');
        $nombreArchivo = $request->input('nombre_archivo');
        $extensionArchivo = $request->input('extension_archivo');
        $file = $request->file('archivo');

        // Verificar si el archivo está vacío
        if ($file->getSize() == 0) {
            return back()->with('error', 'El archivo está vacío y no puede ser subido.');
        }

        // Validar y mapear el tipo MIME
        $validMimeTypes = [
            'doc', 'docx', 'pdf', 'xls', 'xlsx', 'txt'
        ];
        $mimeType = $file->getClientMimeType();
        $extension = $file->getClientOriginalExtension();

        if (!in_array($extension, $validMimeTypes)) {
            return back()->with('error', 'El archivo debe ser un archivo con formato: ' . implode(', ', $validMimeTypes) . '.');
        }

        // Generar un nombre de archivo único
        $uniqueName = Str::uuid() . '.' . $extensionArchivo;

        // Ruta para guardar el archivo
        $rutaArchivo = "archivos/{$tipo}/" . $uniqueName;

        // Crear directorios si no existen
        if (!Storage::exists("public/archivos/{$tipo}")) {
            Storage::makeDirectory("public/archivos/{$tipo}");
        }

        // Guardar el archivo
        $file->storeAs("public/archivos/{$tipo}", $uniqueName);

        $archivoable_type = $tipo === 'clientes' ? Cliente::class : ($tipo === 'basculas' ? Bascula::class : null);

        if (!$archivoable_type) {
            return back()->with('error', 'Tipo de entidad no válido.');
        }

        $tipoAmigable = $this->convertirMimeAAmigable($mimeType);

        $archivo = new Archivo([
            'url_archivo' => $rutaArchivo,
            'nombre_original' => $nombreArchivo,
            'tipo_archivo' => $mimeType,
            'tipo_amigable' => $tipoAmigable,
            'archivoable_id' => $idRelacion,
            'archivoable_type' => $archivoable_type,
        ]);

        if (!$archivo->save()) {
            // Si el archivo no se pudo guardar, devuelve un error
            return back()->with('error', 'No se pudo guardar el archivo.');
        }

        return back()->with('success', 'Archivo añadido con éxito.');
    }

    private function convertirMimeAAmigable($mimeType)
    {
        $mapaTipos = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Excel',
            'application/vnd.ms-excel' => 'Excel',
            'application/pdf' => 'PDF',
            'application/msword' => 'Word',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Word',
            'text/plain' => 'TXT',
        ];

        return $mapaTipos[$mimeType] ?? 'Desconocido';
    }

    private function obtenerIconoParaTipo($tipoAmigable)
{
    $mapaIconos = [
        'Excel' => 'bi bi-file-earmark-spreadsheet',
        'PDF' => 'bi bi-file-earmark-pdf',
        'Word' => 'bi bi-file-earmark-word',
        'TXT' => 'bi bi-file-earmark-text',
        'Desconocido' => 'bi bi-file-earmark',
    ];

    return $mapaIconos[$tipoAmigable] ?? 'bi bi-file-earmark';
}

    public function mostrarArchivos($id, $tipo)
    {
        $modelo = $tipo === 'clientes' ? Cliente::find($id) : Bascula::find($id);

        if (!$modelo) {
            return abort(404);
        }

        $archivos = $modelo->archivos;

        return view('archivos', compact('archivos'));
    }

    public function eliminarArchivo($id)
    {
        $archivo = Archivo::findOrFail($id);

        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        // Eliminar el archivo del almacenamiento
        Storage::disk('public')->delete($archivo->url_archivo);

        // Eliminar el archivo de la base de datos
        $archivo->delete();

        return redirect()->back()->with('success', 'Archivo eliminado con éxito.');
    }

    public function editarNombreArchivo(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255', // Ajusta las validaciones según tus necesidades
    ]);

    $archivo = Archivo::findOrFail($id);
    $archivo->nombre_original = $request->nombre;
    $archivo->save();

    return response()->json(['message' => 'Nombre actualizado correctamente!'], 200);
}

}
