<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

// Recibimos los datos que envía el ProductController
const props = defineProps({
    product: Object,
    relatedProducts: Array
});

const isCartOpen = ref(false);
const selectedSize = ref(null); // Estado para guardar la talla elegida

// Tallas simuladas (esto podría venir de la BD en el futuro)
const sizes = [7, 7.5, 8, 8.5, 9, 9.5, 10, 10.5];

const addToCart = () => {
    // Validación: Obligar a elegir talla
    if (!selectedSize.value) {
        alert('Por favor selecciona una talla para continuar.');
        return;
    }

    // Añadir al carrito global
    cart.add({
        ...props.product,
        // Truco: Agregamos la talla al nombre para diferenciarlo en el carrito
        name: `${props.product.name} (Talla ${selectedSize.value})`,
        selectedSize: selectedSize.value
    });

    // Abrir el carrito automáticamente para confirmar
    isCartOpen.value = true;
};
</script>

<template>
    <Head :title="product.name" />

    <div class="min-h-screen bg-background text-white font-sans selection:bg-primary selection:text-white pb-20">
        <Navbar @openCart="isCartOpen = true" />

        <div class="mx-auto max-w-[1440px] px-6 py-8 mt-20">
            <div class="flex items-center gap-2 py-4 text-xs font-bold uppercase tracking-widest text-gray-500 mb-12">
                <Link :href="route('welcome')" class="hover:text-primary">Inicio</Link>
                <span>/</span>
                <Link :href="route('catalog.index')" class="hover:text-primary">Catálogo</Link>
                <span>/</span>
                <span class="text-white">{{ product.name }}</span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                <div class="lg:col-span-7 space-y-6">
                    <div class="relative aspect-[4/3] rounded-2xl overflow-hidden bg-surface border border-white/5 group">
                        
                        <img 
                            :src="product.image_url && product.image_url !== 'null' 
                                ? '/storage/' + product.image_url 
                                : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=1200&auto=format&fit=crop'"
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
                                {{ product.brand || 'VELOCITY' }}
                            </span>
                            <div class="flex items-center gap-1 text-primary">
                                <span class="material-symbols-outlined text-sm fill-1">star</span>
                                <span class="text-sm font-bold text-white">5.0</span>
                                <span class="text-[10px] text-gray-500">(24 reseñas)</span>
                            </div>
                        </div>
                        
                        <h1 class="text-white text-5xl lg:text-6xl font-black leading-[0.9] tracking-tighter uppercase mb-6 italic">
                            {{ product.name }}
                        </h1>
                        
                        <p class="text-gray-400 text-base leading-relaxed max-w-md">
                            {{ product.description || 'Equipamiento profesional diseñado para dominar el campo con pasión. Materiales de alta resistencia y tecnología aerodinámica.' }}
                        </p>
                        
                        <div class="mt-8 flex items-baseline gap-4">
                            <span class="text-4xl font-black text-white">${{ product.price.toLocaleString() }}</span>
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
                                :class="selectedSize === size 
                                    ? 'border-primary bg-primary text-white shadow-lg shadow-primary/20' 
                                    : 'border-white/10 text-white hover:border-primary hover:text-primary'"
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
                            {{ selectedSize ? 'Añadir al Carrito' : 'Selecciona Talla' }}
                        </button>
                        <p class="text-center text-[10px] font-bold uppercase tracking-widest text-gray-500 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">local_shipping</span>
                            Envío gratis para miembros Elite
                        </p>
                    </div>
                </div>
            </div>

            <div v-if="relatedProducts && relatedProducts.length > 0" class="mt-32 pt-16 border-t border-white/10">
                <h2 class="text-3xl font-black uppercase tracking-tight mb-12 text-white">También te podría gustar</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                     <Link 
                        v-for="related in relatedProducts" 
                        :key="related.id" 
                        :href="route('product.show', related.id)"
                        class="group cursor-pointer"
                    >
                        <div class="aspect-[4/5] bg-surface rounded-xl overflow-hidden relative mb-4 border border-white/5">
                            <img 
                                :src="related.image_url && related.image_url !== 'null' 
                                    ? '/storage/' + related.image_url 
                                    : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&auto=format&fit=crop'"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                                :alt="related.name" 
                            />
                        </div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-white font-display font-bold text-sm uppercase group-hover:text-primary transition-colors">{{ related.name }}</h3>
                                <p class="text-gray-500 text-xs mt-1">{{ related.brand || 'Velocity' }}</p>
                            </div>
                            <span class="text-white font-bold font-display text-sm">${{ related.price.toLocaleString() }}</span>
                        </div>
                    </Link>
                </div>
            </div>

        </div>
        
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>