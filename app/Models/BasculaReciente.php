<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasculaReciente extends Model
{
    use HasFactory;

    protected $table = 'basculas_recientes';

    protected $fillable = [
        'bascula_id',
        'usuario_id',
    ];
}
