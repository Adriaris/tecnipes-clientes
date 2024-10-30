<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
use App\Models\Cliente;
use App\Models\Bascula;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class GaleriaController extends Controller
{
    public function agregarImagen(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image',
            'tipo' => 'required|string',
            'idRelacion' => 'required|integer',
        ]);

        $tipo = $request->input('tipo');
        $idRelacion = $request->input('idRelacion');
        $imageFile = $request->file('imagen');

        // Verificar que el archivo es una imagen válida
        if (!$imageFile->isValid() || !in_array($imageFile->extension(), ['jpeg', 'png', 'bmp', 'gif', 'svg', 'webp', 'jpg'])) {
            return back()->with('error', 'El archivo subido debe ser una imagen');
        }

        // Generar un nombre de archivo único
        $uniqueName = Str::uuid() . '.webp';

        // Rutas para guardar la imagen y el thumbnail
        $rutaImagen = "imagenes/{$tipo}/" . $uniqueName;
        $thumbnailPath = "thumbnails/{$tipo}/" . $uniqueName;

        // Crear directorios si no existen
        if (!Storage::exists("public/imagenes/{$tipo}")) {
            Storage::makeDirectory("public/imagenes/{$tipo}");
        }
        if (!Storage::exists("public/thumbnails/{$tipo}")) {
            Storage::makeDirectory("public/thumbnails/{$tipo}");
        }

        // Convertir la imagen a WebP, comprimirla, corregir la orientación y redimensionarla
        $image = Image::make($imageFile);
        $image->orientate()  // Corregir la orientación según los datos EXIF
            ->resize(1080, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('webp', 100); // 75 es la calidad de la compresión
        $image->save(storage_path('app/public/' . $rutaImagen));

        $imagenable_type = $tipo === 'clientes' ? Cliente::class : ($tipo === 'basculas' ? Bascula::class : null);

        if (!$imagenable_type) {
            return back()->with('error', 'Tipo de entidad no válido.');
        }

        // Obtener las dimensiones de la imagen original redimensionada
        $width = $image->width();
        $height = $image->height();

        // Crear y guardar el thumbnail
        $thumbnail = Image::make($imageFile)->orientate()  // Corregir la orientación del thumbnail
            ->resize(200, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('webp', 100); // 75 es la calidad de la compresión del thumbnail
        $thumbnail->save(storage_path('app/public/' . $thumbnailPath));

        $imagen = new Imagen([
            'url_imagen' => $rutaImagen,
            'thumbnail_url' => $thumbnailPath,
            'width' => $width,
            'height' => $height,
            'imagenable_id' => $idRelacion,
            'imagenable_type' => $imagenable_type,
        ]);

        if (!$imagen->save()) {
            // Si la imagen no se pudo guardar, devuelve un error
            return back()->with('error', 'No se pudo guardar la imagen.');
        }

        return back()->with('success', 'Imagen añadida con éxito.');
    }

    public function eliminarImagen($id)
    {
        $imagen = Imagen::findOrFail($id);

        if (Gate::denies('accessModeratorAndAdmin')) {
            return redirect()->back()->with('error', 'No tienes permiso para realizar esta acción.');
        }

        // Eliminar la imagen y el thumbnail del almacenamiento
        Storage::disk('public')->delete($imagen->url_imagen);
        Storage::disk('public')->delete($imagen->thumbnail_url);

        $imagen->delete();

        return redirect()->back()->with('success', 'Imagen eliminada con éxito.');
    }
}
