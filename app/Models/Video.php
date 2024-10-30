<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', // Asegúrate de que los nombres de los campos coincidan con los de la migración
        'url_video',
        'videoable_id',
        'videoable_type',
    ];

    public function videoable()
    {
        return $this->morphTo();
    }


}
