<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ orders: Array });

// Función para formatear la fecha
const formatDate = (date) => new Date(date).toLocaleDateString();
</script>

<template>
    <Head title="Gestión de Pedidos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight uppercase italic">Órdenes Recibidas ⚽</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 uppercase text-xs font-bold">
                                <th class="p-4 border-b">Cliente</th>
                                <th class="p-4 border-b">Ubicación</th>
                                <th class="p-4 border-b">Productos</th>
                                <th class="p-4 border-b">Total</th>
                                <th class="p-4 border-b">Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50 transition">
                                <td class="p-4 border-b font-bold">{{ order.customer_name }}</td>
                                <td class="p-4 border-b">{{ order.city }} - {{ order.address }}</td>
                                <td class="p-4 border-b">
                                <div v-for="item in order.items" :key="item.id" class="text-xs text-gray-600">
                                    • {{ item.quantity }}x {{ item.name }}
                                </div>
                                </td>
                                <td class="p-4 border-b font-black text-indigo-600">${{ order.total }}</td>
                                <td class="p-4 border-b text-sm">{{ formatDate(order.created_at) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>