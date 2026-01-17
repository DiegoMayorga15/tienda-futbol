<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Esto le da permiso a Laravel para guardar estos datos
    protected $fillable = [
        'customer_name', 
        'address', 
        'city', 
        'phone', 
        'total', 
        'items', 
        'status'
    ];

    // Esto le dice a Laravel que 'items' es un objeto/array y no simple texto
    protected $casts = [
        'items' => 'array',
    ];
}