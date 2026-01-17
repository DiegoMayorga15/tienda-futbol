<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Esta línea es la "llave" que quita el candado de seguridad
    protected $fillable = [
        'name',
        'slug',
    ];
}