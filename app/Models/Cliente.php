<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Scopes\NoTrashScope;


class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'horario',
        'direccion',
        'telefono',
        'persona_contacto',
        'telefono_persona_contacto',
        'nota_cliente',
        'gps',
    ];

    public function basculas()
    {
        return $this->hasMany(Bascula::class, 'id_cliente')->orderBy('created_at', 'desc');
    }

    protected static function booted()
    {
        static::deleting(function ($cliente) {
            if ($cliente->isForceDeleting()) {
                $cliente->basculas()->forceDelete();
            } else {
                $cliente->basculas()->delete();
            }
        });
    }


    public function imagenes()
    {
        return $this->morphMany(Imagen::class, 'imagenable');
    }

    public function archivos()
    {
        return $this->morphMany(Archivo::class, 'archivoable');
    }
    public function videos()
    {
        return $this->morphMany(Video::class, 'videoable');
    }
}
