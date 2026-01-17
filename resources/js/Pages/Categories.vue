<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// ==========================================
// [DATOS] Recepción de datos desde el controlador
// ==========================================
defineProps({
    categories: Array
});

// ==========================================
// [LOGICA] Gestión del Formulario y Acciones
// ==========================================
const form = useForm({
    name: '',
});

// Función para Guardar
const submit = () => {
    form.post(route('categories.store'), {
        onSuccess: () => form.reset(),
    });
};

// Función para Eliminar
const deleteCategory = (id) => {
    if (confirm('¿Estás seguro de eliminar esta categoría? Esto podría afectar a los productos asociados.')) {
        form.delete(route('categories.destroy', id));
    }
};
</script>

<template>
    <Head title="Categorías" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Categorías Deportivas
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    
                    <div class="mb-10 p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <h3 class="text-md font-bold mb-4 text-gray-700 uppercase tracking-wider">
                            Agregar Nueva Categoría
                        </h3>
                        <form @submit.prevent="submit" class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input 
                                    v-model="form.name" 
                                    type="text" 
                                    placeholder="Nombre de la categoría (ej: Calzado de Fútbol)" 
                                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    :class="{ 'border-red-500': form.errors.name }"
                                    required
                                />
                                <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
                                    {{ form.errors.name }}
                                </div>
                            </div>
                            
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                            >
                                <span v-if="form.processing">Guardando...</span>
                                <span v-else>+ Agregar</span>
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de Categoría</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL Amigable (Slug)</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        #{{ category.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 uppercase">
                                        {{ category.name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 font-mono">
                                        /{{ category.slug }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button 
                                            @click="deleteCategory(category.id)"
                                            class="text-red-600 hover:text-red-900 font-bold px-3 py-1 rounded-md hover:bg-red-50 transition"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="categories.length === 0">
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                        No hay categorías registradas. Comienza agregando una arriba.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>