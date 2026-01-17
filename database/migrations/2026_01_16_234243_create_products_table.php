<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Ejemplo: Jersey Selección Colombia 2024
        $table->string('slug')->unique(); // Para URLs amigables: tienda.com/productos/jersey-seleccion-colombia
        $table->text('description')->nullable();
        $table->decimal('price', 10, 2); // Hasta 99,999,999.99
        $table->string('brand'); // Nike, Adidas, Puma...
        $table->string('image_url')->nullable(); // Verifica que el nombre sea exactamente este
        
        // Relación con categorías (luego crearemos la tabla categories)
        $table->foreignId('category_id')->constrained()->onDelete('cascade');

        // Campos de estado y seguridad
        $table->boolean('is_active')->default(true); // Para desactivar productos sin borrarlos
        $table->integer('points_earned')->default(0); // Puntos que otorga este producto (Requisito 4)
        
        $table->timestamps(); // Crea 'created_at' y 'updated_at' automáticamente
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
