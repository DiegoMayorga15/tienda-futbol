<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // ESTRUCTURA CORRECTA (Agrupada)
        $mainGroups = [
            'Ropa' => [
                'Camisetas' => ['Retros', 'Jugador', 'Uniformes'], 
                'Sudaderas' => ['Hombre', 'Dama'],
                'Licras' => ['Buso', 'Pantalón'],
                'Bermudas' => ['Nike', 'Adidas'],
                'Medias' => ['Antideslizantes', 'Profesionales', 'Corrientes']
            ],
            'Calzado' => [
                'Guayos' => ['Talla 25-42'],
                'Torretín' => ['Talla 25-42'],
                'Futsala' => ['Talla 35-42']
            ],
            'Equipamiento' => [
                'Guayeras' => ['Grande', 'Mediana', 'Pequeña'],
                'Tulas' => ['Nike', 'Adidas', 'Dports', 'Saeta'],
                'Guantes Arquero' => ['Adulto', 'Niño'],
                'Canilleras' => [],
                'Accesorios' => ['Bandas Capitán', 'Llaveros', 'Cajas Regalo']
            ],
            'Niños' => [
                'Sudaderas Niños' => [],
                'Uniformes Niños' => [],
                'Retros Niños' => []
            ],
            'Colecciones' => [
                'Gorras' => ['Adidas', 'Nike', 'Fútbol'],
                'Retros General' => []
            ],
            'Ofertas' => [
                'Gran Promoción' => []
            ]
        ];

        foreach ($mainGroups as $rootName => $subGroups) {
            // 1. Crear la Categoría Principal (NIVEL 1 - Aparece en Navbar)
            $root = Category::create([
                'name' => $rootName,
                'slug' => Str::slug($rootName),
                'parent_id' => null,
                'is_active' => true,
            ]);

            foreach ($subGroups as $childName => $grandChildren) {
                // 2. Crear la Subcategoría (NIVEL 2 - Aparece en Desplegable)
                // Usamos un slug compuesto para evitar duplicados (ej: sudaderas-ropa vs sudaderas-ninos)
                $child = Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($rootName . '-' . $childName), 
                    'parent_id' => $root->id,
                    'is_active' => true,
                ]);

                // 3. Crear Sub-items (NIVEL 3 - Opcional, para filtros futuros)
                foreach ($grandChildren as $grandChildName) {
                    Category::create([
                        'name' => $grandChildName,
                        'slug' => Str::slug($childName . '-' . $grandChildName . '-' . Str::random(4)),
                        'parent_id' => $child->id,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}