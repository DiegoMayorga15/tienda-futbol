<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Admin Dashboard" />
    
    <AdminLayout>
        <div class="mb-8">
            <h2 class="text-3xl font-black text-gray-800 uppercase italic">Hola, Administrador 👋</h2>
            <p class="text-gray-500">Aquí tienes un resumen de tu tienda hoy.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-4 bg-blue-50 text-blue-600 rounded-lg">
                    <span class="material-symbols-outlined text-3xl">inventory_2</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Productos</p>
                    <p class="text-3xl font-black text-gray-900">{{ stats.products }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-4 bg-purple-50 text-purple-600 rounded-lg">
                    <span class="material-symbols-outlined text-3xl">category</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Categorías</p>
                    <p class="text-3xl font-black text-gray-900">{{ stats.categories }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="p-4 bg-green-50 text-green-600 rounded-lg">
                    <span class="material-symbols-outlined text-3xl">attach_money</span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-wide">Ventas Mes</p>
                    <p class="text-3xl font-black text-gray-900">$0</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>