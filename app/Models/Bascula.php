<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\NoTrashScope;


class Bascula extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'instrumento',
        'indicador',
        'fabricante',
        'modelo',
        'numero_serie',
        'codigo',
        'ubicacion',
        'maximo',
        'unidad_medida_kg_g',
        'escalon',
        'division', // Cambiado de 'divisiones' a 'division'
        'acabado',
        'instalacion',
        'dimensiones',
        'numero_apoyos',
        'tipo_apoyo',
        'modelo_celula',
        'cap_celula',
        'nota_bascula',
        'operativa',
        'datos_completos',
        'id_cliente',
    ];

    protected $attributes = [
        'operativa' => true,
        'datos_completos' => false,
    ];



    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente');
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
