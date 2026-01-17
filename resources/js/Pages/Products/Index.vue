<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

// ==========================================
// [DATOS] Recibimos la lista de productos
// ==========================================
defineProps({
    products: Array
});

const form = useForm({});

// ==========================================
// [LOGICA] Función para eliminar productos
// ==========================================
const deleteProduct = (id) => {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
        form.delete(route('products.destroy', id));
    }
};
</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Inventario de Productos</h2>
                <Link 
                    :href="route('products.create')" 
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700 transition"
                >
                    + Agregar Producto
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Foto</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre / Marca</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Categoría</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img 
                                            v-if="product.image_url" 
                                            :src="'/storage/' + product.image_url" 
                                            class="h-12 w-12 object-cover rounded-md border"
                                            alt="Foto producto"
                                        />
                                        <div v-else class="h-12 w-12 bg-gray-100 rounded-md flex items-center justify-center text-gray-400 text-xs text-center">
                                            Sin foto
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ product.name }}</div>
                                        <div class="text-xs text-gray-500">{{ product.brand }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">
                                            {{ product.category ? product.category.name : 'Sin categoría' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        ${{ product.price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</button>
                                        <button 
                                            @click="deleteProduct(product.id)" 
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div v-if="products.length === 0" class="text-center py-10 text-gray-500 italic">
                            No hay productos en el inventario. Haz clic en "Agregar Producto" para comenzar.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>