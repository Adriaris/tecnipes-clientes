<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class Imagen extends Model
{
    protected $table = 'imagenes';
    use HasFactory;

    protected $fillable = [
        'url_imagen',
        'thumbnail_url',
        'imagenable_id',
        'imagenable_type',
        'width',
        'height'
    ];



    public function imagenable()
    {
        return $this->morphTo();
    }
}
