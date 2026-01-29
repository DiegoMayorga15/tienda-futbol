<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

// Recibimos los datos desde el controlador de Laravel
const props = defineProps({
    products: Array,
    categories: Array,
    filters: Object
});

const isCartOpen = ref(false);

// Iniciamos el precio con lo que venga del filtro o un valor alto por defecto
const price = ref(props.filters.price || 500000); 

// Función para añadir al carrito
const addToCart = (product) => {
    cart.add(product);
    isCartOpen.value = true;
};

// Función para filtrar por categoría
const filterProducts = (categoryName) => {
    router.get(route('catalog.index'), { 
        category: categoryName,
        price: price.value 
    }, { 
        preserveState: true, 
        preserveScroll: true 
    });
};

// Observar cambios en el precio (con retraso para no recargar a cada milímetro)
let timeout = null;
watch(price, (newPrice) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('catalog.index'), { 
            ...props.filters, // Mantiene la categoría actual si existe
            price: newPrice 
        }, { preserveState: true, preserveScroll: true, replace: true });
    }, 500);
});
</script>

<template>
    <Head title="Catálogo" />

    <div class="min-h-screen bg-background text-white font-sans selection:bg-primary selection:text-white pb-20">
        <Navbar @openCart="isCartOpen = true" />

        <div class="mx-auto max-w-[1440px] px-6 py-8 mt-20">
            <div class="flex items-center gap-2 py-4 text-xs font-bold uppercase tracking-widest text-gray-500 mb-8">
                <Link :href="route('welcome')" class="hover:text-primary">Inicio</Link>
                <span>/</span>
                <span class="text-white">Catálogo</span>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                
                <aside class="w-full lg:w-64 flex-none space-y-10">
                    <div>
                        <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
                            <h2 class="text-lg font-black uppercase tracking-wide">Filtros</h2>
                            <Link :href="route('catalog.index')" class="text-[10px] font-bold text-gray-500 hover:text-primary uppercase tracking-widest">
                                Limpiar
                            </Link>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <h3 class="text-sm font-bold uppercase text-white">Categoría</h3>
                            <div class="space-y-3">
                                <label v-for="cat in categories" :key="cat.id" class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="category"
                                        :checked="filters.category === cat.name"
                                        @change="filterProducts(cat.name)"
                                        class="size-4 rounded-full bg-transparent border-white/20 checked:bg-primary checked:border-primary focus:ring-0" 
                                    />
                                    <span class="text-xs font-bold uppercase text-gray-400 group-hover:text-white transition-colors">
                                        {{ cat.name }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold uppercase text-white">Precio Máximo</h3>
                                <span class="text-sm font-bold text-primary">${{ price.toLocaleString() }}</span>
                            </div>
                            <input 
                                type="range" 
                                min="0" 
                                max="1000000" 
                                step="10000"
                                v-model="price"
                                class="w-full h-1 bg-white/10 rounded-full appearance-none accent-primary cursor-pointer" 
                            />
                        </div>
                    </div>
                </aside>

                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 pb-4 border-b border-white/10 gap-4">
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-500">
                            Mostrando <span class="text-white">{{ products.length }}</span> Productos
                        </p>
                    </div>

                    <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link 
                            v-for="product in products" 
                            :key="product.id" 
                            :href="route('product.show', product.id)"
                            class="group relative flex flex-col bg-surface border border-white/5 overflow-hidden rounded-xl hover:border-primary/30 transition-all cursor-pointer shadow-2xl"
                        >
                            <div class="relative aspect-[4/5] overflow-hidden bg-background">
                                <div class="absolute top-0 left-0 z-10 bg-primary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest rounded-br-lg">
                                    {{ product.category?.name || 'Pro' }}
                                </div>
                                
                                <img 
                                    :src="product.image_url && product.image_url !== 'null' 
                                        ? '/storage/' + product.image_url 
                                        : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&auto=format&fit=crop'"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                                    :alt="product.name" 
                                />
                                
                                <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <button 
                                        @click.prevent="addToCart(product)"
                                        class="w-full bg-white text-black font-display font-bold text-sm py-3 rounded uppercase tracking-wide hover:bg-primary hover:text-white transition-colors"
                                    >
                                        Añadir al Carrito
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-5 flex flex-col h-full">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1">
                                    {{ product.brand || 'VELOCITY' }}
                                </span>
                                <h3 class="text-lg font-bold uppercase text-white group-hover:text-primary transition-colors leading-tight mb-4">
                                    {{ product.name }}
                                </h3>
                                <div class="mt-auto flex items-center justify-between">
                                    <span class="text-xl font-black text-white">${{ product.price.toLocaleString() }}</span>
                                </div>
                            </div>
                        </Link>
                    </div>
                    
                    <div v-else class="text-center py-20 bg-surface rounded-xl border border-white/5 border-dashed">
                        <span class="material-symbols-outlined text-6xl text-gray-600 mb-4">search_off</span>
                        <h3 class="text-xl font-bold text-white uppercase">No hay productos</h3>
                        <p class="text-gray-500 text-sm mt-2">Intenta ajustar los filtros de precio o categoría.</p>
                        <Link :href="route('catalog.index')" class="mt-4 inline-block text-primary font-bold uppercase text-xs tracking-widest hover:underline">
                            Limpiar todos los filtros
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>