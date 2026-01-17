<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

// Recibimos las categorías desde el controlador para el selector
defineProps({ categories: Array });

const form = useForm({
    name: '',
    description: '',
    price: '',
    brand: '',
    category_id: '',
    image: null, // Para la foto del producto
});

const submit = () => {
    // Usamos form.post para enviar datos y archivos
    form.post(route('products.store'), {
        forceFormData: true, // Obligatorio para enviar imágenes
    });
};
</script>

<template>
    <Head title="Nuevo Producto" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Agregar Artículo Deportivo</h2>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Nombre del Producto</label>
                                <input v-model="form.name" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Marca (Nike, Adidas, etc)</label>
                                <input v-model="form.brand" type="text" class="w-full border-gray-300 rounded-md shadow-sm" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Categoría</label>
                                <select v-model="form.category_id" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                    <option value="" disabled>Selecciona una categoría</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-medium text-sm text-gray-700">Precio (USD)</label>
                                <input v-model="form.price" type="number" step="0.01" class="w-full border-gray-300 rounded-md shadow-sm" required />
                            </div>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Descripción</label>
                            <textarea v-model="form.description" class="w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Foto del Producto</label>
                            <input @input="form.image = $event.target.files[0]" type="file" class="w-full mt-1" accept="image/*" />
                        </div>

                        <div class="flex items-center justify-end gap-4">
                            <Link :href="route('products.index')" class="text-sm text-gray-600 underline">Cancelar</Link>
                            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md font-bold" :disabled="form.processing">
                                Guardar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>