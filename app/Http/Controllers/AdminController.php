<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'products' => Product::count(),
                'categories' => Category::count(),
                // Aquí agregaremos 'orders' y 'users' más adelante
            ]
        ]);
    }

    public function categories()
    {
        // Traemos solo las categorías principales (Ropa, Calzado...) con sus hijos
        $categories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return Inertia::render('Admin/Categories', [
            'categories' => $categories
        ]);
    }

    public function storeCategory(Request $request)
    {
        // 1. Validamos que el nombre sea obligatorio
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id' // El padre es opcional
        ]);

        // 2. Creamos la categoría
        Category::create([
            'name' => $request->name,
            // Generamos el Slug automáticamente (ej: "Ropa Retro" -> "ropa-retro")
            'slug' => \Illuminate\Support\Str::slug($request->name . '-' . rand(1, 1000)), 
            'parent_id' => $request->parent_id,
            'is_active' => true
        ]);

        // 3. Regresamos (Inertia recargará la lista automáticamente)
        return back();
    }

// --- EDITAR CATEGORÍA ---
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        // Evitar que una categoría sea su propio padre (loop infinito)
        if ($request->parent_id == $category->id) {
            return back()->withErrors(['parent_id' => 'Una categoría no puede ser su propio padre.']);
        }

        $category->update([
            'name' => $request->name,
            // Opcional: Actualizar slug si cambia el nombre, o dejarlo igual
            'parent_id' => $request->parent_id,
        ]);

        return back();
    }

    // --- BORRAR CATEGORÍA ---
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Opción segura: Si tiene hijos, borramos los hijos también (o podriamos dejarlos huérfanos)
        // Por ahora, borrado simple. La base de datos se encarga del resto si configuramos cascada, 
        // si no, Laravel intentará borrar.
        $category->delete();

        return back();
    }

   // --- LISTAR (Actualizado para traer imágenes) ---
    public function products()
    {
        // Agregamos 'images' a la consulta
        $products = Product::with(['category', 'images'])->latest()->get();
        $categories = Category::whereNotNull('parent_id')->get();

        return Inertia::render('Admin/Products', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    // --- GUARDAR (Soporte Múltiple) ---
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'images' => 'nullable|array|max:6', // Máximo 6 fotos
            'images.*' => 'image|max:2048' // Cada una máx 2MB
        ]);

        // 1. Crear el producto
        $product = Product::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name . '-' . rand(1, 1000)),
            'description' => $request->description,
            'price' => $request->price,
            'brand' => $request->brand,
            'category_id' => $request->category_id,
            // Guardamos la primera imagen como "Principal" en la tabla productos para compatibilidad
            'image_url' => null, 
        ]);

        // 2. Procesar las imágenes
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                
                // Guardar en la tabla galería
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path
                ]);

                // Si es la primera, la ponemos como portada en la tabla principal
                if ($index === 0) {
                    $product->update(['image_url' => $path]);
                }
            }
        }

        return back();
    }

    // --- ACTUALIZAR (Editar) ---
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'images' => 'nullable|array|max:6',
        ]);

        // 1. Actualizar textos
        $product->update([
            'name' => $request->name,
            'brand' => $request->brand,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        // 2. Si subió NUEVAS fotos, las agregamos a la galería existente
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                // Verificar que no exceda el límite total (opcional)
                if ($product->images()->count() >= 6) break;

                $path = $file->store('products', 'public');
                
                \App\Models\ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path
                ]);

                // Si el producto no tenía portada, poner esta
                if (!$product->image_url) {
                    $product->update(['image_url' => $path]);
                }
            }
        }

        return back();
    }

    // --- BORRAR FOTO INDIVIDUAL ---
    public function deleteImage($id)
    {
        $image = \App\Models\ProductImage::findOrFail($id);
        
        // Borrar archivo del disco
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($image->image_url)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image_url);
        }

        // Si esta era la foto de portada, limpiar el campo en el producto
        $product = $image->product;
        if ($product->image_url === $image->image_url) {
            // Intentar buscar otra foto para que sea la nueva portada
            $nextImage = $product->images()->where('id', '!=', $id)->first();
            $product->update(['image_url' => $nextImage ? $nextImage->image_url : null]);
        }

        $image->delete();
        return back();
    }
}