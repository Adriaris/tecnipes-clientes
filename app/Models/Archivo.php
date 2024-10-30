<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Archivo extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'url_archivo',
        'nombre_original',
        'tipo_archivo',
        'tipo_amigable',
        'archivoable_id',
        'archivoable_type',
    ];

    /**
     * Obtener todas las entidades poseedoras del archivo.
     */
    public function archivoable()
    {
        return $this->morphTo();
    }



}
