<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { cart } from '@/cartStore.js';

const props = defineProps({
    isOpen: Boolean
});

const emit = defineEmits(['close']);

// Calcular total
const total = computed(() => {
    return cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
});

// Lógica de Envío Gratis (Ejemplo: Gratis a partir de $200.000)
const freeShippingThreshold = 200000;
const shippingProgress = computed(() => Math.min((total.value / freeShippingThreshold) * 100, 100));
const missingForFreeShipping = computed(() => Math.max(freeShippingThreshold - total.value, 0));
</script>

<template>
    <div class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        
        <Transition
            enter-active-class="ease-in-out duration-500"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="ease-in-out duration-500"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="isOpen" class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity" @click="$emit('close')"></div>
        </Transition>

        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute inset-0 overflow-hidden">
                <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                    
                    <Transition
                        enter-active-class="transform transition ease-in-out duration-500 sm:duration-700"
                        enter-from-class="translate-x-full"
                        enter-to-class="translate-x-0"
                        leave-active-class="transform transition ease-in-out duration-500 sm:duration-700"
                        leave-from-class="translate-x-0"
                        leave-to-class="translate-x-full"
                    >
                        <div v-if="isOpen" class="pointer-events-auto w-screen max-w-md">
                            <div class="flex h-full flex-col bg-white shadow-2xl">
                                
                                <div class="flex items-start justify-between px-6 py-6 border-b border-gray-100">
                                    <h2 class="text-2xl font-black text-gray-900 uppercase italic tracking-tighter" id="slide-over-title">
                                        Tu Carrito <span class="text-primary text-sm not-italic font-bold align-top">({{ cart.items.length }})</span>
                                    </h2>
                                    <button type="button" class="relative -m-2 p-2 text-gray-400 hover:text-black transition-colors" @click="$emit('close')">
                                        <span class="material-symbols-outlined">close</span>
                                    </button>
                                </div>

                                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100" v-if="cart.items.length > 0">
                                    <div v-if="missingForFreeShipping > 0">
                                        <p class="text-xs font-bold uppercase tracking-wide text-gray-600 mb-2">
                                            Te faltan <span class="text-black">${{ missingForFreeShipping.toLocaleString() }}</span> para envío gratis
                                        </p>
                                        <div class="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-black transition-all duration-1000 ease-out" :style="{ width: shippingProgress + '%' }"></div>
                                        </div>
                                    </div>
                                    <div v-else class="flex items-center gap-2 text-green-600">
                                        <span class="material-symbols-outlined text-sm">local_shipping</span>
                                        <p class="text-xs font-bold uppercase tracking-wide">¡Genial! Tienes envío gratis</p>
                                    </div>
                                </div>

                                <div class="flex-1 overflow-y-auto px-6 py-6 scrollbar-hide">
                                    <div v-if="cart.items.length > 0" class="flow-root">
                                        <ul role="list" class="-my-6 divide-y divide-gray-100">
                                            <li v-for="item in cart.items" :key="item.id + item.selectedSize" class="flex py-6 group">
                                                
                                                <div class="h-28 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-gray-50 relative">
                                                    <img 
                                                        :src="item.image_url && item.image_url !== 'null' ? '/storage/' + item.image_url : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=200&fit=crop'" 
                                                        class="h-full w-full object-cover object-center mix-blend-multiply transition-transform group-hover:scale-105"
                                                    >
                                                </div>

                                                <div class="ml-4 flex flex-1 flex-col">
                                                    <div>
                                                        <div class="flex justify-between text-base font-medium text-gray-900">
                                                            <h3 class="font-bold uppercase text-sm leading-tight pr-4">
                                                                <a :href="route('product.show', item.id)">{{ item.name }}</a>
                                                            </h3>
                                                            <p class="ml-4 font-black">${{ (item.price * item.quantity).toLocaleString() }}</p>
                                                        </div>
                                                        <p class="mt-1 text-xs text-gray-500 font-bold uppercase tracking-widest">{{ item.brand || 'VELOCITY' }}</p>
                                                        <p v-if="item.selectedSize" class="mt-1 text-xs text-gray-500">Talla: <span class="text-black font-bold">{{ item.selectedSize }}</span></p>
                                                    </div>
                                                    
                                                    <div class="flex flex-1 items-end justify-between text-sm">
                                                        <div class="flex items-center border border-gray-200 rounded-sm">
                                                            <button @click="cart.decrease(item)" class="px-2 py-1 hover:bg-gray-100 text-gray-600 font-bold">-</button>
                                                            <span class="px-2 text-xs font-bold">{{ item.quantity }}</span>
                                                            <button @click="cart.increase(item)" class="px-2 py-1 hover:bg-gray-100 text-gray-600 font-bold">+</button>
                                                        </div>

                                                        <button type="button" @click="cart.remove(item)" class="flex items-center gap-1 font-medium text-red-500 hover:text-red-700 text-xs uppercase tracking-wider transition-colors">
                                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                                            Quitar
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div v-else class="h-full flex flex-col items-center justify-center text-center space-y-4 opacity-50">
                                        <span class="material-symbols-outlined text-6xl text-gray-300">shopping_bag</span>
                                        <p class="text-lg font-bold text-gray-900 uppercase">Tu carrito está vacío</p>
                                        <p class="text-sm text-gray-500 max-w-xs mx-auto">Parece que aún no has encontrado tu equipamiento ideal.</p>
                                        <button @click="$emit('close')" class="mt-4 text-primary font-bold uppercase text-xs underline underline-offset-4">Volver a la tienda</button>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 px-6 py-6 bg-gray-50">
                                    <div class="flex justify-between text-base font-black text-gray-900 mb-2">
                                        <p class="uppercase">Subtotal</p>
                                        <p>${{ total.toLocaleString() }}</p>
                                    </div>
                                    <p class="mt-0.5 text-xs text-gray-500 mb-6">Impuestos y envío calculados al finalizar.</p>
                                    
                                    <div class="space-y-3">
                                        <Link 
                                            :href="route('dashboard')" 
                                            class="flex items-center justify-center w-full bg-black px-6 py-4 text-base font-black text-white shadow-lg hover:bg-primary transition-colors uppercase tracking-widest group"
                                        >
                                            Pagar Ahora <span class="material-symbols-outlined ml-2 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                        </Link>
                                        
                                        <button 
                                            @click="$emit('close')"
                                            class="flex w-full items-center justify-center px-6 py-3 text-xs font-bold text-gray-900 uppercase tracking-widest hover:underline"
                                        >
                                            O continuar comprando
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Ocultar scrollbar pero permitir scroll */
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>