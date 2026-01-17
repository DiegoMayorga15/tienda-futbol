<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order; // Importante: Importamos el modelo para guardar

class OrderController extends Controller
{
    /**
     * Muestra la página de Checkout (donde el cliente ve el formulario)
     */
    public function index()
    {
        return Inertia::render('Checkout/Index');
    }

    /**
     * Guarda el pedido en la base de datos (se activa al dar clic en Confirmar)
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'address' => 'required|string',
        'city' => 'required|string',
        'phone' => 'required|string',
        'items' => 'required|array', // Verificamos que sea un array
        'total' => 'required|numeric',
    ]);

    Order::create([
        'customer_name' => $validated['name'],
        'address' => $validated['address'],
        'city' => $validated['city'],
        'phone' => $validated['phone'],
        'items' => $validated['items'], // Lo pasamos directo
        'total' => $validated['total'],
    ]);

    return redirect()->route('welcome');
    }

    public function adminIndex()
{
    return Inertia::render('Admin/Orders/Index', [
        'orders' => Order::latest()->get()
    ]);
}
}