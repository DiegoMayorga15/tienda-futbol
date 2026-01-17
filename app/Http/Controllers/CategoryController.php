<?php

namespace App\Http\Controllers;

use App\Models\Category; // Importante añadir esto
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        // Ahora sí enviamos las categorías reales a la vista
        return Inertia::render('Categories', [
            'categories' => Category::all()
        ]);
    }

    public function store(Request $request)
    {
        // Validamos que el nombre sea obligatorio y único
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
        ]);

        // Guardamos en la base de datos
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // Crea "ropa-deportiva" de "Ropa Deportiva"
        ]);

        return redirect()->back(); // Refresca la página para mostrar los cambios
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back();
    }   
}