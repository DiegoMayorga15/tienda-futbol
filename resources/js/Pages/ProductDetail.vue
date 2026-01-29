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
const selectedSize = ref(null);

// Tallas disponibles
const sizes = [7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5];

const addToCart = () => {
    if (!selectedSize.value) {
        alert('Por favor selecciona una talla para continuar.');
        return;
    }

    cart.add({
        ...props.product,
        name: `${props.product.name} (Talla ${selectedSize.value})`,
        selectedSize: selectedSize.value
    });

    isCartOpen.value = true;
};
</script>

<template>
    <Head :title="product.name" />

    <div class="min-h-screen bg-white text-gray-900 font-sans selection:bg-primary selection:text-white pb-20">
        <Navbar @openCart="isCartOpen = true" />

        <div class="mx-auto max-w-[1440px] px-6 py-8 mt-20">
            <div class="flex items-center gap-2 py-4 text-xs font-bold uppercase tracking-widest text-gray-500 mb-12">
                <Link :href="route('welcome')" class="hover:text-primary transition-colors">Inicio</Link>
                <span>/</span>
                <Link :href="route('catalog.index')" class="hover:text-primary transition-colors">Catálogo</Link>
                <span>/</span>
                <span class="text-black">{{ product.name }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
                
                <div class="lg:col-span-7 space-y-6">
                    <div class="relative aspect-[4/4] rounded-xl overflow-hidden bg-gray-100 border border-gray-200 group shadow-sm">
                        <div class="absolute top-6 left-6 z-10">
                             <span class="bg-black text-white text-[10px] font-black px-4 py-2 uppercase tracking-widest">
                                {{ product.brand || 'VELOCITY' }}
                            </span>
                        </div>

                        <img 
                            :src="product.image_url && product.image_url !== 'null' 
                                ? '/storage/' + product.image_url 
                                : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=1200&auto=format&fit=crop'"
                            class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-105"
                            :alt="product.name" 
                        />
                    </div>
                    
                    <div class="grid grid-cols-4 gap-4">
                        <div v-for="n in 4" :key="n" class="aspect-square bg-gray-100 rounded-lg cursor-pointer hover:ring-2 ring-black transition-all">
                             </div>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col pt-4">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-primary font-bold uppercase tracking-[0.2em] text-xs">Nueva Colección</span>
                            <div class="flex text-yellow-500 text-sm gap-0.5">
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="material-symbols-outlined fill-1">star</span>
                                <span class="text-gray-400 text-xs ml-2 mt-0.5">(24 reviews)</span>
                            </div>
                        </div>
                        
                        <h1 class="text-black text-4xl lg:text-5xl font-black leading-[1] tracking-tighter uppercase mb-6 italic">
                            {{ product.name }}
                        </h1>
                        
                        <p class="text-gray-600 text-base leading-relaxed mb-8">
                            {{ product.description || 'Domina el campo con tecnología de punta. Diseñado para velocidad explosiva y control total bajo cualquier condición climática.' }}
                        </p>
                        
                        <div class="mb-8 pb-8 border-b border-gray-100">
                            <span class="text-5xl font-black text-gray-900 tracking-tight">${{ product.price.toLocaleString() }}</span>
                            <span class="block text-green-600 text-xs font-bold uppercase mt-2">Disponible en inventario • Envío Inmediato</span>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-black uppercase tracking-widest text-black">Selecciona Talla (US)</h3>
                            <button class="text-[10px] font-bold text-gray-500 underline underline-offset-4 hover:text-primary">Guía de tallas</button>
                        </div>
                        <div class="grid grid-cols-4 gap-3">
                            <button 
                                v-for="size in sizes" 
                                :key="size" 
                                @click="selectedSize = size"
                                class="h-12 border-2 font-bold text-sm transition-all flex items-center justify-center relative overflow-hidden rounded hover:border-black"
                                :class="selectedSize === size 
                                    ? 'border-primary bg-primary text-white shadow-lg shadow-primary/30' 
                                    : 'border-gray-200 text-gray-900 bg-white'"
                            >
                                {{ size }}
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4 mt-auto">
                        <button 
                            @click="addToCart"
                            class="group w-full h-16 bg-black hover:bg-primary active:scale-[0.99] transition-all text-white font-black text-lg uppercase tracking-widest shadow-xl flex items-center justify-center gap-3 rounded overflow-hidden relative"
                        >
                            <span class="relative z-10 flex items-center gap-3">
                                <span class="material-symbols-outlined">shopping_bag</span>
                                {{ selectedSize ? 'Añadir al Carrito' : 'Selecciona Talla' }}
                            </span>
                        </button>
                        
                        <p class="text-center text-[10px] font-bold uppercase tracking-widest text-gray-400 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">local_shipping</span>
                            Envío Gratis en compras superiores a $200.000
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="relatedProducts && relatedProducts.length > 0" class="mt-32 pt-16 border-t-2 border-gray-100">
                <div class="flex justify-between items-end mb-12">
                    <h2 class="text-4xl font-black uppercase italic tracking-tight text-black">Completa tu <span class="text-primary">Kit</span></h2>
                    <Link :href="route('catalog.index')" class="hidden md:block text-xs font-bold uppercase tracking-widest border-b-2 border-black pb-1 hover:text-primary hover:border-primary transition-all">Ver todo</Link>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                     <Link 
                        v-for="related in relatedProducts" 
                        :key="related.id" 
                        :href="route('product.show', related.id)"
                        class="group cursor-pointer block"
                    >
                        <div class="aspect-[4/5] bg-gray-100 rounded-lg overflow-hidden relative mb-4 border border-gray-200">
                            <img 
                                :src="related.image_url && related.image_url !== 'null' 
                                    ? '/storage/' + related.image_url 
                                    : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&auto=format&fit=crop'"
                                class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500" 
                                :alt="related.name" 
                            />
                            <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <div class="bg-white text-black size-10 flex items-center justify-center rounded-full shadow-lg">
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </div>
                             </div>
                        </div>
                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest">{{ related.brand || 'Velocity' }}</p>
                            <h3 class="text-gray-900 font-bold text-sm uppercase group-hover:text-primary transition-colors mt-1">{{ related.name }}</h3>
                            <span class="block mt-2 text-black font-black text-lg">${{ related.price.toLocaleString() }}</span>
                        </div>
                    </Link>
                </div>
            </div>

        </div>
        
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>