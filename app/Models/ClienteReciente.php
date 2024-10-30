<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteReciente extends Model
{
    use HasFactory;

    protected $table = 'clientes_recientes';

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'fecha_visita'
    ];
}
