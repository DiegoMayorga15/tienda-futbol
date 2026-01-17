<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { cart } from '@/cartStore.js';

const form = useForm({
    name: '',
    address: '',
    phone: '',
    city: '',
    items: cart.items, // Enviamos lo que hay en el carrito
    total: cart.items.reduce((total, item) => total + (item.price * item.quantity), 0)
});

const submitOrder = () => {
    // Verificamos en consola antes de enviar (opcional para pruebas)
    console.log(form.items); 

    form.post(route('orders.store'), {
        onSuccess: () => {
            cart.items = [];
            cart.save();
            alert('¡Pedido realizado con éxito!');
        },
    });
};
</script>

<template>
    <Head title="Finalizar Compra" />

    <div class="min-h-screen bg-gray-50 py-12 px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-3xl font-black text-gray-900 mb-8 uppercase italic">Finalizar Pedido</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold mb-6 border-b pb-2">Datos de Envío</h3>
                    <form @submit.prevent="submitOrder" class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase">Nombre Completo</label>
                            <input v-model="form.name" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase">Dirección</label>
                            <input v-model="form.address" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase">Ciudad</label>
                                <input v-model="form.city" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase">Teléfono</label>
                                <input v-model="form.phone" type="tel" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                            </div>
                        </div>
                        <button type="submit" class="w-full mt-6 bg-indigo-600 text-white py-4 rounded-full font-black uppercase italic hover:bg-indigo-700 transition shadow-lg">
                            Confirmar Pedido (${{ form.total }})
                        </button>
                    </form>
                </div>

                <div class="bg-gray-100 p-8 rounded-2xl">
                    <h3 class="text-xl font-bold mb-6">Resumen de Compra</h3>
                    <ul class="divide-y divide-gray-300">
                        <li v-for="item in cart.items" :key="item.id" class="py-4 flex justify-between italic">
                            <span>{{ item.quantity }}x {{ item.name }}</span>
                            <span class="font-bold">${{ item.price * item.quantity }}</span>
                        </li>
                    </ul>
                    <div class="border-t border-gray-400 mt-6 pt-6 flex justify-between text-xl font-black uppercase">
                        <span>Total</span>
                        <span>${{ form.total }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>