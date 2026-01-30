<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController; // <--- Importante para el panel nuevo
use App\Models\Product; // <--- Importante para el catálogo
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. RUTA DE INICIO (Welcome)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// 2. RUTA DEL CATÁLOGO (Corregida)
// IMPORTANTE: No pasamos 'categories' aquí para no romper el menú del Navbar.
Route::get('/catalog', function () {
    return Inertia::render('Catalog', [
        'products' => Product::all(), 
    ]);
})->name('catalog.index');

// 3. RUTA DE DETALLE DE PRODUCTO (La usaremos pronto)
Route::get('/product/{id}', function ($id) {
    return Inertia::render('ProductDetail', [
        'product' => Product::findOrFail($id)
    ]);
})->name('product.show');

// 4. RUTAS DE USUARIO LOGUEADO (Dashboard Cliente)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. RUTAS DEL ADMINISTRADOR
Route::middleware(['auth'])->prefix('admin')->group(function () {
    
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Rutas de Categorías
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
    
    // --- NUEVAS RUTAS (Editar y Borrar) ---
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('admin.categories.destroy');

    // --- RUTAS DE PRODUCTOS ---
    Route::get('/products', [AdminController::class, 'products'])->name('admin.products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('admin.products.destroy');


    // 1. Actualizar producto (Texto y subir más fotos)
    Route::post('/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    
    // 2. Borrar una foto específica de la galería
    Route::delete('/product-images/{id}', [AdminController::class, 'deleteImage'])->name('admin.product-images.destroy');
    });
require __DIR__.'/auth.php';