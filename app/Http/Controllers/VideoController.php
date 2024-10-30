<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Cliente;
use App\Models\Bascula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function agregarVideo(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'video' => 'required|file|max:25600|mimetypes:video/mp4,video/mpeg,video/quicktime',
            'tipo' => 'required|string',
            'idRelacion' => 'required|integer',
        ]);

        $nombre = $request->input('nombre');
        $tipo = $request->input('tipo');
        $idRelacion = $request->input('idRelacion');
        $videoFile = $request->file('video');

        // Verificar que el archivo es un video válido
        $validMimeTypes = ['video/mp4', 'video/mpeg', 'video/quicktime'];
        $mimeType = $videoFile->getClientMimeType();

        if (!in_array($mimeType, $validMimeTypes)) {
            return back()->with('error', 'El archivo subido no es un video válido.');
        }

        // Generar un nombre de archivo único para el video
        $videoUniqueName = Str::uuid() . '.' . $videoFile->getClientOriginalExtension();

        // Ruta para guardar el video
        $rutaVideo = "videos/{$tipo}/" . $videoUniqueName;

        // Crear directorio si no existe
        $videoDirectory = "public/videos/{$tipo}";
        if (!Storage::exists($videoDirectory)) {
            Storage::makeDirectory($videoDirectory);
        }

        // Mover el video al almacenamiento
        try {
            $videoFile->storeAs($videoDirectory, $videoUniqueName);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar el archivo: ' . $e->getMessage());
        }

        $videoable_type = $tipo === 'clientes' ? Cliente::class : ($tipo === 'basculas' ? Bascula::class : null);

        if (!$videoable_type) {
            return back()->with('error', 'Tipo de entidad no válido.');
        }

        $video = new Video([
            'nombre' => $nombre,
            'url_video' => $rutaVideo,
            'videoable_id' => $idRelacion,
            'videoable_type' => $videoable_type,
        ]);

        if (!$video->save()) {
            return back()->with('error', 'No se pudo guardar el video.');
        }

        return back()->with('success', 'Video añadido con éxito.');
    }

    public function editarNombreVideo(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255', // Ajusta las validaciones según tus necesidades
        ]);

        $video = Video::findOrFail($id);
        $video->nombre = $request->nombre;
        $video->save();

        return response()->json(['message' => 'Nombre actualizado correctamente!'], 200);
    }




    public function eliminarVideo($id)
    {
        $video = Video::findOrFail($id);

        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        // Eliminar el video del almacenamiento
        Storage::disk('public')->delete($video->url_video);

        // Eliminar el video de la base de datos
        $video->delete();

        return redirect()->back()->with('success', 'Video eliminado con éxito.');
    }
}
