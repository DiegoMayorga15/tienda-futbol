<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

const props = defineProps({
    product: Object,
    relatedProducts: Array
});

const isCartOpen = ref(false);
const selectedSize = ref(null); // Variable para la talla seleccionada

const addToCart = () => {
    if (!selectedSize.value) {
        alert('Por favor selecciona una talla');
        return;
    }
    
    // Añadimos al carrito con la talla seleccionada
    cart.add({
        ...props.product,
        name: `${props.product.name} (Talla ${selectedSize.value})`
    });
    
    isCartOpen.value = true;
};

// Tallas de ejemplo (podrías guardarlas en DB en el futuro)
const sizes = [7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5];
</script>

<template>
    <Head :title="product.name" />
    
    <div class="min-h-screen bg-background text-white font-sans selection:bg-primary selection:text-white pb-20">
        <Navbar @openCart="isCartOpen = true" />

        <div class="mx-auto max-w-[1440px] px-6 py-8 mt-20">
            <div class="flex items-center gap-2 py-4 text-xs font-bold uppercase tracking-widest text-gray-500 mb-12">
                <Link :href="route('welcome')" class="hover:text-primary">Inicio</Link>
                <span>/</span>
                <span class="text-white">{{ product.name }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <div class="lg:col-span-7 space-y-6">
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-surface border border-white/5 group">
                        <img 
                            :src="'/storage/' + product.image_url" 
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                            :alt="product.name" 
                        />
                        <div class="absolute bottom-4 left-4 z-20 flex items-center gap-2 px-3 py-1 bg-primary text-white rounded-full">
                            <span class="material-symbols-outlined text-sm">360</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Vista 360</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col gap-10">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest border border-primary/20">
                                {{ product.category ? product.category.name : 'Edición Limitada' }}
                            </span>
                            <div class="flex items-center gap-1 text-primary">
                                <span class="material-symbols-outlined text-sm fill-1">star</span>
                                <span class="text-sm font-bold text-white">5.0</span>
                            </div>
                        </div>
                        
                        <h1 class="text-white text-5xl lg:text-6xl font-black leading-[0.9] tracking-tighter uppercase mb-6 italic">
                            {{ product.name }}
                        </h1>
                        
                        <p class="text-gray-400 text-base leading-relaxed max-w-md">
                            {{ product.description || 'Diseñados para dominar el campo con pasión. Rendimiento profesional garantizado.' }}
                        </p>
                        
                        <div class="mt-8 flex items-baseline gap-4">
                            <span class="text-4xl font-black text-white">${{ product.price }}</span>
                        </div>
                    </div>

                    <hr class="border-white/10" />

                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xs font-black uppercase tracking-widest text-white">Selecciona Talla (US)</h3>
                            <button class="text-[10px] font-bold text-primary underline underline-offset-4">Guía de tallas</button>
                        </div>
                        <div class="grid grid-cols-4 gap-2">
                            <button 
                                v-for="size in sizes" 
                                :key="size" 
                                @click="selectedSize = size"
                                class="h-12 border font-bold text-sm uppercase transition-all flex items-center justify-center relative overflow-hidden"
                                :class="selectedSize === size ? 'border-primary bg-primary text-white shadow-lg shadow-primary/20' : 'border-white/10 text-white hover:border-primary hover:text-primary'"
                            >
                                {{ size }}
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <button 
                            @click="addToCart"
                            class="w-full h-16 bg-primary hover:bg-red-700 active:scale-[0.98] transition-all text-white font-black text-lg uppercase tracking-widest shadow-xl shadow-primary/30 flex items-center justify-center gap-3"
                        >
                            <span class="material-symbols-outlined font-bold">shopping_bag</span>
                            Añadir al Carrito
                        </button>
                        <p class="text-center text-[10px] font-bold uppercase tracking-widest text-gray-500 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">local_shipping</span>
                            Envío gratis para miembros Elite
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>