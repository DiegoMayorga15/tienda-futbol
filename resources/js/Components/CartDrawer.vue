<script setup>
import { cart } from '@/cartStore.js';
import { Link } from '@inertiajs/vue3';

defineProps({ isOpen: Boolean });
defineEmits(['close']);
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-[100] overflow-hidden">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="$emit('close')"></div>

        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div class="w-screen max-w-md">
                <div class="h-full flex flex-col bg-white shadow-xl">
                    <div class="flex-1 py-6 overflow-y-auto px-4 sm:px-6">
                        <div class="flex items-start justify-between">
                            <h2 class="text-lg font-bold text-gray-900 uppercase">Tu Carrito ⚽</h2>
                            <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500 text-2xl">×</button>
                        </div>

                        <div class="mt-8">
                            <div v-if="cart.items.length === 0" class="text-center py-10">
                                <p class="text-gray-500 italic">El carrito está vacío. ¡Ve por esos goles!</p>
                            </div>
                            <ul class="divide-y divide-gray-200">
                                <li v-for="item in cart.items" :key="item.id" class="py-6 flex">
                                    <div class="flex-shrink-0 w-24 h-24 border border-gray-200 rounded-md overflow-hidden">
                                        <img :src="'/storage/' + item.image_url" class="w-full h-full object-cover" />
                                    </div>
                                    <div class="ml-4 flex-1 flex flex-col">
                                        <div>
                                            <div class="flex justify-between text-base font-medium text-gray-900">
                                                <h3>{{ item.name }}</h3>
                                                <p class="ml-4">${{ item.price * item.quantity }}</p>
                                            </div>
                                            <p class="mt-1 text-sm text-gray-500">{{ item.brand }}</p>
                                        </div>
                                        <div class="flex-1 flex items-end justify-between text-sm">
                                            <p class="text-gray-500">Cant: {{ item.quantity }}</p>
                                            <button @click="cart.remove(item.id)" class="font-medium text-red-600 hover:text-red-500 underline">Quitar</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 py-6 px-4 sm:px-6">
                        <div class="flex justify-between text-base font-bold text-gray-900">
                            <p>Subtotal</p>
                            <p>${{ cart.items.reduce((total, item) => total + (item.price * item.quantity), 0) }}</p>
                        </div>
                        <p class="mt-0.5 text-sm text-gray-500">Envío calculado en el pago.</p>
                        <div class="mt-6">
                            <Link 
                                :href="route('checkout.index')"
                                class="w-full flex justify-center items-center px-6 py-3 border border-transparent rounded-full shadow-sm text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 uppercase italic transition"
                            >
                                Ir a Pagar 💳
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>