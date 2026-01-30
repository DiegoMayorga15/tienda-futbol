<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    products: Array,
    categories: Array
});

const isModalOpen = ref(false);
const isEditing = ref(false);
const editingProduct = ref(null);

const form = useForm({
    name: '',
    brand: '',
    price: '',
    category_id: '',
    description: '',
    images: [], // Array para nuevas fotos
});

// ABRIR PARA CREAR
const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    editingProduct.value = null; // Limpiamos producto seleccionado
    isModalOpen.value = true;
};

// ABRIR PARA EDITAR
const openEditModal = (product) => {
    isEditing.value = true;
    editingProduct.value = product; // Guardamos el producto completo para mostrar sus fotos actuales
    
    // Llenamos el formulario
    form.name = product.name;
    form.brand = product.brand;
    form.price = product.price;
    form.category_id = product.category_id;
    form.description = product.description;
    form.images = []; // Reseteamos nuevas imágenes
    
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    editingProduct.value = null;
};

// GUARDAR (Lógica dual)
const submitForm = () => {
    if (isEditing.value) {
        // ACTUALIZAR (Usamos post con _method override si fuera necesario, pero Inertia maneja archivos mejor con post)
        router.post(route('admin.products.update', editingProduct.value.id), {
            _method: 'put', // Truco para enviar archivos en update
            ...form
        }, {
            onSuccess: () => closeModal(),
            forceFormData: true, // Vital para subir archivos
        });
    } else {
        // CREAR
        form.post(route('admin.products.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// BORRAR FOTO INDIVIDUAL (De la galería existente)
const deleteGalleryImage = (imageId) => {
    if (confirm('¿Borrar esta foto?')) {
        router.delete(route('admin.product-images.destroy', imageId), {
            preserveScroll: true,
            // Opcional: Recargar localmente si quisieras, pero Inertia lo hará
        });
    }
};

const deleteProduct = (product) => {
    if (confirm('¿Borrar producto completo?')) {
        router.delete(route('admin.products.destroy', product.id));
    }
};

const formatPrice = (value) => {
    return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(value);
};
</script>

<template>
    <Head title="Administrar Productos" />

    <AdminLayout>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-black italic uppercase text-gray-800">Productos</h2>
                <p class="text-sm text-gray-500">Gestión de inventario y multimedia.</p>
            </div>
            <button @click="openCreateModal" class="bg-black text-white text-xs font-bold uppercase px-4 py-3 rounded hover:bg-primary transition-colors flex items-center gap-2 shadow-lg">
                <span class="material-symbols-outlined text-sm">add_photo_alternate</span> Nuevo Producto
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Portada</th>
                        <th class="px-6 py-4">Info</th>
                        <th class="px-6 py-4">Fotos Extra</th>
                        <th class="px-6 py-4">Precio</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="w-16 h-16 bg-gray-100 rounded overflow-hidden border border-gray-200">
                                <img v-if="product.image_url" :src="'/storage/' + product.image_url" class="w-full h-full object-cover">
                                <span v-else class="flex items-center justify-center h-full text-[10px] text-gray-400">Sin Foto</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-black uppercase">{{ product.name }}</div>
                            <div class="text-[10px] text-gray-400 font-bold tracking-wider">{{ product.brand }}</div>
                            <span class="text-[10px] bg-gray-100 px-1 rounded">{{ product.category?.name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center justify-center bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full">
                                {{ product.images ? product.images.length : 0 }} / 6
                            </span>
                        </td>
                        <td class="px-6 py-4 font-black text-gray-900">
                            {{ formatPrice(product.price) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="openEditModal(product)" class="text-blue-600 hover:text-black font-bold text-xs uppercase mr-3">Editar</button>
                            <button @click="deleteProduct(product)" class="text-red-500 hover:text-black font-bold text-xs uppercase">Borrar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-black uppercase italic text-gray-900 mb-4">
                    {{ isEditing ? 'Editar Producto' : 'Nuevo Producto' }}
                </h2>
                
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Nombre" />
                            <TextInput v-model="form.name" type="text" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel value="Marca" />
                            <TextInput v-model="form.brand" type="text" class="mt-1 block w-full" required />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel value="Precio" />
                            <TextInput v-model="form.price" type="number" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <InputLabel value="Categoría" />
                            <select v-model="form.category_id" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm" required>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mt-4">
                        <InputLabel value="Galería de Imágenes (Máx 6)" />
                        
                        <div v-if="isEditing && editingProduct.images && editingProduct.images.length > 0" class="mb-4">
                            <p class="text-[10px] text-gray-400 uppercase font-bold mb-2">Imágenes actuales (Clic en X para borrar):</p>
                            <div class="flex flex-wrap gap-2">
                                <div v-for="img in editingProduct.images" :key="img.id" class="relative group w-16 h-16 rounded overflow-hidden border border-gray-200">
                                    <img :src="'/storage/' + img.image_url" class="w-full h-full object-cover">
                                    <button type="button" @click="deleteGalleryImage(img.id)" class="absolute inset-0 bg-black/50 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="material-symbols-outlined text-sm">close</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <input 
                                type="file" 
                                multiple 
                                @input="form.images = $event.target.files" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-black file:text-white hover:file:bg-primary transition-colors" 
                                accept="image/*"
                            />
                            <p class="text-[10px] text-gray-400 mt-1">Puedes seleccionar varios archivos a la vez (Ctrl + Clic).</p>
                        </div>
                    </div>

                    <div>
                        <InputLabel value="Descripción" />
                        <textarea v-model="form.description" class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm" rows="2"></textarea>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-4 py-2 text-xs font-bold uppercase text-gray-600 hover:text-black">Cancelar</button>
                        <PrimaryButton :disabled="form.processing">
                            {{ isEditing ? 'Guardar Cambios' : 'Crear Producto' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>