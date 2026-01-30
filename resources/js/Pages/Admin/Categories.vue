<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3'; // Importamos router para borrar
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    categories: Array
});

// Estado del Modal
const isModalOpen = ref(false);
const isEditing = ref(false); // ¿Estamos creando o editando?
const editingId = ref(null);  // ID de la categoría que editamos

const form = useForm({
    name: '',
    parent_id: null,
});

// ABRIR PARA CREAR
const openCreateModal = () => {
    isEditing.value = false;
    form.reset();
    isModalOpen.value = true;
};

// ABRIR PARA EDITAR (Recibe la categoría y rellena el formulario)
const openEditModal = (category) => {
    isEditing.value = true;
    editingId.value = category.id;
    
    // Rellenamos el form con los datos actuales
    form.name = category.name;
    form.parent_id = category.parent_id;
    
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
    isEditing.value = false;
};

// GUARDAR (Decide si es Crear o Actualizar)
const submitForm = () => {
    if (isEditing.value) {
        // ACTUALIZAR (PUT)
        form.put(route('admin.categories.update', editingId.value), {
            onSuccess: () => closeModal(),
        });
    } else {
        // CREAR (POST)
        form.post(route('admin.categories.store'), {
            onSuccess: () => closeModal(),
        });
    }
};

// BORRAR
const deleteCategory = (category) => {
    if (confirm(`¿Estás seguro de borrar "${category.name}"? Se borrarán también sus subcategorías.`)) {
        router.delete(route('admin.categories.destroy', category.id));
    }
};
</script>

<template>
    <Head title="Administrar Categorías" />

    <AdminLayout>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-black italic uppercase text-gray-800">Categorías</h2>
                <p class="text-sm text-gray-500">Organiza el menú de tu tienda.</p>
            </div>
            <button @click="openCreateModal" class="bg-black text-white text-xs font-bold uppercase px-4 py-3 rounded hover:bg-primary transition-colors flex items-center gap-2 shadow-lg">
                <span class="material-symbols-outlined text-sm">add</span> Nueva Categoría
            </button>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-4">Categoría Principal</th>
                        <th class="px-6 py-4">Subcategorías (Hijos)</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-black uppercase align-top">
                            {{ category.name }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <span v-if="category.children.length === 0" class="text-xs text-gray-400 italic">Sin subcategorías</span>
                                
                                <span v-for="child in category.children" :key="child.id" 
                                      class="group relative px-2 py-1 bg-gray-100 rounded text-[10px] font-bold uppercase tracking-wide text-gray-600 border border-gray-200 hover:bg-gray-200 cursor-pointer"
                                      @click="openEditModal(child)" title="Clic para editar esta subcategoría">
                                    {{ child.name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right align-top">
                            <button @click="openEditModal(category)" class="text-blue-600 hover:text-black font-bold text-xs uppercase mr-3 transition-colors">
                                Editar
                            </button>
                            <button @click="deleteCategory(category)" class="text-red-500 hover:text-black font-bold text-xs uppercase transition-colors">
                                Borrar
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-black uppercase italic text-gray-900 mb-4">
                    {{ isEditing ? 'Editar Categoría' : 'Crear Nueva Categoría' }}
                </h2>

                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nombre" />
                        <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel for="parent" value="Ubicación (Padre)" />
                        <select id="parent" v-model="form.parent_id" 
                                class="mt-1 block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                            <option :value="null">Es Principal (Ej: ROPA)</option>
                            <option v-for="parent in categories" 
                                    :key="parent.id" 
                                    :value="parent.id"
                                    :disabled="isEditing && parent.id === editingId">
                                {{ parent.name }}
                            </option>
                        </select>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" @click="closeModal" class="px-4 py-2 text-xs font-bold uppercase text-gray-600 hover:text-black">
                            Cancelar
                        </button>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ isEditing ? 'Guardar Cambios' : 'Crear' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>