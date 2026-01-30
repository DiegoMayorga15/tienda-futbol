<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

// --- CAMBIO CLAVE: Valor por defecto para evitar errores ---
const props = defineProps({
    products: {
        type: Array,
        default: () => [] // Si no llegan productos, usa un array vacío
    },
});

const page = usePage();
// Protección: Si categories no llega, usa array vacío
const categories = computed(() => page.props.categories || []);

const isCartOpen = ref(false);

const addToCart = (product) => {
    cart.add(product);
    isCartOpen.value = true;
};

const formatPrice = (value) => {
    // Protección: Si el precio no es un número, retorna 0
    if (!value) return '$0';
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value);
};
</script>

<template>
    <Head title="Catálogo" />

    <div class="min-h-screen bg-white text-gray-900 font-sans selection:bg-primary selection:text-white">
        
        <Navbar @openCart="isCartOpen = true" />

        <div class="pt-32 pb-10 bg-gray-50 px-6 text-center border-b border-gray-200">
            <h1 class="font-display font-black text-4xl md:text-5xl italic tracking-tighter uppercase">
                Catálogo <span class="text-primary">2026</span>
            </h1>
            <p class="text-xs font-bold uppercase tracking-[0.3em] text-gray-400 mt-2">
                Explora nuestra colección completa
            </p>
        </div>

        <div class="max-w-[1600px] mx-auto px-6 py-12 flex flex-col lg:flex-row gap-12">
            
            <aside class="w-full lg:w-64 flex-shrink-0 space-y-8">
                <div>
                    <h3 class="font-black italic text-lg uppercase mb-4 border-b-2 border-black pb-2">Categorías</h3>
                    <ul class="space-y-2">
                        <li>
                            <Link :href="route('catalog.index')" 
                                  class="text-sm font-bold uppercase text-gray-500 hover:text-primary transition-colors flex items-center gap-2">
                                <span class="w-1 h-1 bg-gray-300 rounded-full"></span> Ver Todo
                            </Link>
                        </li>
                        
                        <li v-for="cat in categories" :key="cat.id">
                            <details class="group">
                                <summary class="list-none flex justify-between items-center cursor-pointer text-sm font-bold uppercase text-gray-800 hover:text-primary py-1">
                                    {{ cat.name }}
                                    <span v-if="cat.children && cat.children.length" class="material-symbols-outlined text-xs transition-transform group-open:rotate-180">expand_more</span>
                                </summary>
                                
                                <ul class="pl-4 mt-1 space-y-1 border-l border-gray-200 ml-1" v-if="cat.children && cat.children.length">
                                    <li v-for="child in cat.children" :key="child.id">
                                        <Link :href="route('catalog.index', { category: child.slug })" 
                                              class="text-xs font-semibold text-gray-500 hover:text-black uppercase block py-1">
                                            {{ child.name }}
                                        </Link>
                                    </li>
                                </ul>
                            </details>
                        </li>
                    </ul>
                </div>
            </aside>

            <main class="flex-1">
                <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-100">
                    <span class="text-xs font-bold uppercase text-gray-500">{{ products?.length || 0 }} Productos encontrados</span>
                </div>

                <div v-if="products && products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-12">
                    <Link 
                        v-for="product in products" 
                        :key="product.id" 
                        :href="route('product.show', product.id)"
                        class="group block relative"
                    >
                        <div class="aspect-[4/5] bg-gray-100 rounded-lg overflow-hidden relative mb-4 shadow-sm group-hover:shadow-xl transition-all duration-500 group-hover:-translate-y-1">
                            <div class="absolute top-3 left-3 bg-black text-white text-[9px] font-black px-3 py-1 uppercase z-10 shadow-lg tracking-widest">
                                Nuevo
                            </div>
                            
                            <img 
                                :src="product.image_url && product.image_url !== 'null' ? '/storage/' + product.image_url : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&fit=crop'"
                                class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-110"
                                alt="Producto"
                            />

                            <button 
                                @click.prevent="addToCart(product)" 
                                class="absolute bottom-3 right-3 bg-white/90 backdrop-blur text-black size-10 rounded-full flex items-center justify-center shadow-lg hover:bg-primary hover:text-white transition-all duration-300 hover:scale-110"
                            >
                                <span class="material-symbols-outlined text-lg">shopping_bag</span>
                            </button>
                        </div>

                        <div>
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">{{ product.brand || 'DPORTS' }}</p>
                            <h3 class="text-gray-900 font-black text-sm uppercase leading-tight mb-2 group-hover:text-primary transition-colors truncate">
                                {{ product.name }}
                            </h3>
                            <span class="text-lg font-bold text-gray-900 border-b-2 border-primary/20 pb-0.5">
                                {{ formatPrice(product.price) }}
                            </span>
                        </div>
                    </Link>
                </div>

                <div v-else class="py-20 text-center">
                    <span class="material-symbols-outlined text-6xl text-gray-200 mb-4">sentiment_dissatisfied</span>
                    <h3 class="text-xl font-black italic uppercase text-gray-400">No encontramos productos</h3>
                    <p class="text-gray-500 text-sm mt-2">Intenta cambiar los filtros o busca otra categoría.</p>
                </div>
            </main>
        </div>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
        
        <footer class="bg-black text-white py-12 border-t border-gray-800 mt-auto">
            <div class="max-w-[1440px] mx-auto px-6 text-center">
                <h3 class="font-display font-black text-3xl italic tracking-tighter mb-4">D<span class="text-primary">PORTS</span></h3>
                <p class="text-gray-500 text-xs uppercase tracking-[0.3em]">Todos los derechos reservados &copy; 2026</p>
            </div>
        </footer>
    </div>
</template>