<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'parent_id', 'is_active', 'order', 'image_url'];

    // Relación: Una categoría puede tener un "Papá" (Ej: "Guayos" pertenece a "Calzado")
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relación: Una categoría puede tener muchos "Hijos" (Ej: "Ropa" tiene "Camisetas", "Sudaderas")
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order', 'asc');
    }
    
    // Relación: Una categoría tiene productos (asumiendo que ya tienes o tendrás el modelo Product)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}