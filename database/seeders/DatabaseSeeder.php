<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;

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

        // 2. Crear Categorías
        $catHombre = Category::firstOrCreate(['name' => 'Hombre'], ['slug' => 'hombre']);
        $catMujer = Category::firstOrCreate(['name' => 'Mujer'], ['slug' => 'mujer']);
        $catAccesorios = Category::firstOrCreate(['name' => 'Accesorios'], ['slug' => 'accesorios']);

        // 3. Lista de Productos (AHORA CON BRAND Y SLUG)
        $productos = [
            // Productos HOMBRE
            [
                'name' => 'Camiseta Velocity Pro 2026',
                'slug' => 'camiseta-velocity-pro-2026',
                'brand' => 'Velocity', // <--- ¡Esto faltaba!
                'description' => 'La camiseta oficial de la temporada. Tecnología transpirable y corte aerodinámico para máxima velocidad.',
                'price' => 120000,
                'category_id' => $catHombre->id,
                'image_url' => 'null', 
            ],
            [
                'name' => 'Guayos Predator Elite',
                'slug' => 'guayos-predator-elite',
                'brand' => 'Adidas', // <--- Agregado
                'description' => 'Control total del balón con la nueva superficie texturizada. Suela de carbono para arranques explosivos.',
                'price' => 450000,
                'category_id' => $catHombre->id,
                'image_url' => 'null',
            ],
            [
                'name' => 'Shorts de Entrenamiento Negro',
                'slug' => 'shorts-entrenamiento-negro',
                'brand' => 'Nike', // <--- Agregado
                'description' => 'Comodidad ligera para tus sesiones más intensas. Cintura elástica y bolsillos con cierre.',
                'price' => 65000, 
                'category_id' => $catHombre->id,
                'image_url' => 'null',
            ],

            // Productos MUJER
            [
                'name' => 'Camiseta Strike Femenina',
                'slug' => 'camiseta-strike-femenina',
                'brand' => 'Puma', // <--- Agregado
                'description' => 'Diseño ajustado y ergonómico. Tejido que absorbe el sudor para mantenerte seca los 90 minutos.',
                'price' => 115000,
                'category_id' => $catMujer->id,
                'image_url' => 'null',
            ],
            [
                'name' => 'Guayos Speedflow Rosa',
                'slug' => 'guayos-speedflow-rosa',
                'brand' => 'Adidas', // <--- Agregado
                'description' => 'Velocidad inigualable. Diseñados específicamente para el pie femenino con soporte extra en el arco.',
                'price' => 380000,
                'category_id' => $catMujer->id,
                'image_url' => 'null',
            ],

            // Productos ACCESORIOS
            [
                'name' => 'Balón Oficial Match Ball',
                'slug' => 'balon-oficial-match-ball',
                'brand' => 'FIFA', // <--- Agregado
                'description' => 'Certificado por la FIFA. Vuelo preciso y durabilidad extrema en cualquier clima.',
                'price' => 90000, 
                'category_id' => $catAccesorios->id,
                'image_url' => 'null',
            ],
        ];

        // 4. Insertar Productos
        foreach ($productos as $prod) {
            Product::firstOrCreate(
                ['slug' => $prod['slug']], 
                $prod 
            );
        }
    }
}