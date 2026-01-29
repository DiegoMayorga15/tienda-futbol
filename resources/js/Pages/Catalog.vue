<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

const props = defineProps({
    products: Array,
    categories: Array,
    filters: Object
});

const isCartOpen = ref(false);
const price = ref(props.filters.price || 500000); 

const addToCart = (product) => {
    cart.add(product);
    isCartOpen.value = true;
};

const filterProducts = (categoryName) => {
    router.get(route('catalog.index'), { 
        category: categoryName,
        price: price.value 
    }, { preserveState: true, preserveScroll: true });
};

let timeout = null;
watch(price, (newPrice) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(route('catalog.index'), { 
            ...props.filters,
            price: newPrice 
        }, { preserveState: true, preserveScroll: true, replace: true });
    }, 500);
});
</script>

<template>
    <Head title="Catálogo" />

    <div class="min-h-screen bg-white text-gray-900 font-sans selection:bg-primary selection:text-white pb-20">
        <Navbar @openCart="isCartOpen = true" />

        <div class="mx-auto max-w-[1440px] px-6 py-8 mt-20">
            <div class="flex items-center gap-2 py-4 text-xs font-bold uppercase tracking-widest text-gray-400 mb-8">
                <Link :href="route('welcome')" class="hover:text-primary transition-colors">Inicio</Link>
                <span>/</span>
                <span class="text-black">Catálogo</span>
            </div>

            <div class="flex flex-col lg:flex-row gap-12">
                
                <aside class="w-full lg:w-64 flex-none space-y-10">
                    <div>
                        <div class="flex items-center justify-between border-b-2 border-black pb-4 mb-6">
                            <h2 class="text-lg font-black uppercase tracking-wide">Filtros</h2>
                            <Link :href="route('catalog.index')" class="text-[10px] font-bold text-gray-400 hover:text-primary uppercase tracking-widest">
                                Limpiar
                            </Link>
                        </div>
                        
                        <div class="space-y-4 mb-8">
                            <h3 class="text-sm font-bold uppercase text-black">Categoría</h3>
                            <div class="space-y-3">
                                <label v-for="cat in categories" :key="cat.id" class="flex items-center gap-3 cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="category"
                                        :checked="filters.category === cat.name"
                                        @change="filterProducts(cat.name)"
                                        class="size-4 rounded-full bg-gray-100 border-gray-300 checked:bg-primary checked:border-primary focus:ring-0 text-primary" 
                                    />
                                    <span class="text-xs font-bold uppercase text-gray-500 group-hover:text-black transition-colors">
                                        {{ cat.name }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-bold uppercase text-black">Precio Máximo</h3>
                                <span class="text-sm font-bold text-primary">${{ price.toLocaleString() }}</span>
                            </div>
                            <input 
                                type="range" 
                                min="0" 
                                max="1000000" 
                                step="10000"
                                v-model="price"
                                class="w-full h-1 bg-gray-200 rounded-full appearance-none accent-primary cursor-pointer hover:accent-black transition-all" 
                            />
                        </div>
                    </div>
                </aside>

                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
                        <p class="text-sm font-bold uppercase tracking-widest text-gray-500">
                            Mostrando <span class="text-black">{{ products.length }}</span> Resultados
                        </p>
                    </div>

                    <div v-if="products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <Link 
                            v-for="(product, index) in products" 
                            :key="product.id" 
                            :href="route('product.show', product.id)"
                            class="group relative flex flex-col bg-white border border-gray-100 rounded-lg hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden"
                        >
                            <div class="relative aspect-[4/5] overflow-hidden bg-gray-100">
                                <div class="absolute top-0 left-0 z-10 bg-primary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">
                                    {{ product.category?.name || 'Pro' }}
                                </div>
                                
                                <img 
                                    :src="product.image_url && product.image_url !== 'null' 
                                        ? '/storage/' + product.image_url 
                                        : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&auto=format&fit=crop'"
                                    class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-700" 
                                    :alt="product.name" 
                                />
                                
                                <div class="absolute bottom-0 left-0 w-full translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    <button 
                                        @click.prevent="addToCart(product)"
                                        class="w-full bg-black text-white font-bold text-xs py-4 uppercase tracking-widest hover:bg-primary transition-colors"
                                    >
                                        Añadir al Carrito
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-5 flex flex-col h-full bg-white relative z-20">
                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 group-hover:text-primary transition-colors">
                                    {{ product.brand || 'VELOCITY' }}
                                </span>
                                <h3 class="text-base font-bold uppercase text-gray-900 leading-tight mb-4 h-10 overflow-hidden">
                                    {{ product.name }}
                                </h3>
                                <div class="mt-auto flex items-center justify-between pt-4 border-t border-gray-100">
                                    <span class="text-lg font-black text-gray-900">${{ product.price.toLocaleString() }}</span>
                                    <div class="flex text-yellow-400 text-xs">
                                        <span class="material-symbols-outlined text-[16px]">star</span>
                                        <span class="text-gray-400 font-bold ml-1 text-[10px] mt-0.5">4.8</span>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                    
                    <div v-else class="text-center py-20 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">search_off</span>
                        <h3 class="text-xl font-bold text-gray-900 uppercase">Sin resultados</h3>
                        <p class="text-gray-500 text-sm mt-2">Intenta ajustar los filtros de precio o categoría.</p>
                        <Link :href="route('catalog.index')" class="mt-6 inline-block bg-black text-white px-6 py-2 rounded font-bold uppercase text-xs hover:bg-primary transition-colors">
                            Limpiar filtros
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>