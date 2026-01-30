<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Ropa", "Guayos"
            $table->string('slug')->unique(); // Ej: "ropa", "guayos-pro"
            $table->unsignedBigInteger('parent_id')->nullable(); // Aquí está la magia (Categoría Padre)
            $table->boolean('is_active')->default(true); // Para ocultar/mostrar sin borrar
            $table->integer('order')->default(0); // Para ordenar el menú
            $table->string('image_url')->nullable(); // Por si quieres ponerle foto a la categoría
            $table->timestamps();

            // Relación con ella misma (Padre -> Hijo)
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};