<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde registras las rutas para tu aplicación.
|
*/

/**
 * [PUBLICO] Ruta de Bienvenida / Catálogo
 * Esta es la página principal que verán tus clientes.
 */
Route::get('/', function (Request $request) {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        // Añadimos la lógica de búsqueda
        'products' => Product::query()
            ->with('category')
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%");
            })
            ->latest()
            ->get(),
        'filters' => $request->only(['search']), // Para mantener el texto en el buscador
    ]);
})->name('welcome');

/**
 * [PRIVADO] Rutas Protegidas (Requieren Login)
 */
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Principal
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // --- Módulo de Categorías ---
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // --- Módulo de Productos ---
    // Usamos 'resource' para manejar Index, Create, Store, Edit, Update y Destroy de una vez
    Route::resource('products', ProductController::class);

    // --- Módulo de Carrito ---
    Route::get('/checkout', [OrderController::class, 'index'])->name('checkout.index');

    Route::get('/admin/orders', [OrderController::class, 'adminIndex'])->name('admin.orders.index');


    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    // --- Gestión de Perfil (Breeze) ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de Autenticación (Login, Registro, etc.)
require __DIR__.'/auth.php';