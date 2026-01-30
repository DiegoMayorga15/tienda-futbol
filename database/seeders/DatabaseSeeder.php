<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category; // Importante
use App\Models\Product;  // Importante

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Usuario Admin
        User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Administrador',
                'password' => bcrypt('password'),
            ]
        );

        // 2. Ejecutar el sembrador de la estructura de categorías (Ropa, Calzado, etc.)
        $this->call(CategorySeeder::class);

        // 3. Obtener algunas categorías REALES para asignarles productos
        // Buscamos por el 'slug' que generamos en CategorySeeder
        $catCamisetas = Category::where('slug', 'ropa-camisetas')->first();
        $catGuayos = Category::where('slug', 'calzado-guayos')->first();
        $catBalones = Category::where('slug', 'equipamiento-accesorios')->first(); // Asignamos balones a accesorios por ahora

        // Si por alguna razón no existen (ej: cambiaste el seeder), usamos fallback
        $idCamisetas = $catCamisetas ? $catCamisetas->id : 1;
        $idGuayos = $catGuayos ? $catGuayos->id : 1;
        $idAccesorios = $catBalones ? $catBalones->id : 1;

        // 4. Lista de Productos de Prueba
        $productos = [
            [
                'name' => 'Camiseta Velocity Pro 2026',
                'slug' => 'camiseta-velocity-pro-2026',
                'brand' => 'Velocity',
                'description' => 'La camiseta oficial de la temporada. Tecnología transpirable y corte aerodinámico.',
                'price' => 120000,
                'category_id' => $idCamisetas,
                'image_url' => 'null', 
            ],
            [
                'name' => 'Guayos Predator Elite',
                'slug' => 'guayos-predator-elite',
                'brand' => 'Adidas',
                'description' => 'Control total del balón con la nueva superficie texturizada. Suela de carbono.',
                'price' => 450000,
                'category_id' => $idGuayos,
                'image_url' => 'null',
            ],
            [
                'name' => 'Shorts de Entrenamiento Negro',
                'slug' => 'shorts-entrenamiento-negro',
                'brand' => 'Nike',
                'description' => 'Comodidad ligera para tus sesiones más intensas. Cintura elástica.',
                'price' => 65000, 
                'category_id' => $idCamisetas, // Lo ponemos en ropa por ahora
                'image_url' => 'null',
            ],
            [
                'name' => 'Camiseta Strike Femenina',
                'slug' => 'camiseta-strike-femenina',
                'brand' => 'Puma',
                'description' => 'Diseño ajustado y ergonómico. Tejido que absorbe el sudor.',
                'price' => 115000,
                'category_id' => $idCamisetas,
                'image_url' => 'null',
            ],
            [
                'name' => 'Guayos Speedflow Rosa',
                'slug' => 'guayos-speedflow-rosa',
                'brand' => 'Adidas',
                'description' => 'Velocidad inigualable. Diseñados específicamente para el pie femenino.',
                'price' => 380000,
                'category_id' => $idGuayos,
                'image_url' => 'null',
            ],
            [
                'name' => 'Balón Oficial Match Ball',
                'slug' => 'balon-oficial-match-ball',
                'brand' => 'FIFA',
                'description' => 'Certificado por la FIFA. Vuelo preciso y durabilidad extrema.',
                'price' => 90000, 
                'category_id' => $idAccesorios,
                'image_url' => 'null',
            ],
        ];

        // 5. Insertar Productos
        foreach ($productos as $prod) {
            Product::firstOrCreate(
                ['slug' => $prod['slug']], 
                $prod 
            );
        }
    }
}