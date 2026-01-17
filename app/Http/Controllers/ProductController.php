<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Products/Index', [
            'products' => Product::with('category')->latest()->get(),
            'categories' => Category::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('Products/Create', [
            'categories' => \App\Models\Category::all()
        ]);
    }
    public function store(Request $request)
    {
        // 1. Validamos los datos (Seguridad primero)
        $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // Max 2MB
        ]);

        // 2. Manejo de la imagen
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Guardamos la imagen en la carpeta 'products' dentro de 'storage/app/public'
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // 3. Crear el producto en la base de datos
        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . rand(1000, 9999), // Slug único
            'brand' => $request->brand,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image_url' => $imagePath, // Guardamos la ruta de la foto
        ]);

        // 4. Redirigir al listado con un mensaje de éxito
        return redirect()->route('products.index');
    }
}