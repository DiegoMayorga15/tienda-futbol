<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { cart } from '@/cartStore.js';
import CartDrawer from '@/Components/CartDrawer.vue';


const isCartOpen = ref(false);
// ==========================================
// [DATOS] Recepción de datos desde el controlador
// ==========================================
const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    products: Array,
    filters: Object,
});


// ==========================================
// [LOGICA] Sistema de Búsqueda Dinámica
// ==========================================
let search = ref(props.filters?.search || '');

// Observa cambios en el buscador y actualiza la URL automáticamente
watch(search, (value) => {
    router.get('/', { search: value }, {
        preserveState: true,
        replace: true,
    });
});

const addToCart = (product) => {
    cart.add(product);
    alert(`${product.name} añadido al carrito ⚽`);
};
</script>

<template>
    <Head title="Tienda de Fútbol - Inicio" />

    <div class="min-h-screen bg-gray-100">
<nav class="bg-white shadow-sm p-4 flex justify-between items-center sticky top-0 z-50">
    <h1 class="text-2xl font-black text-indigo-600 tracking-tighter italic">TIENDA FÚTBOL</h1>

    <div class="flex items-center space-x-6">
        <button @click="isCartOpen = true" class="relative group flex items-center">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-600 group-hover:text-indigo-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <span class="text-[10px] font-bold uppercase text-gray-400 group-hover:text-indigo-600">Carrito</span>
            </div>
            
            <span v-if="cart.totalItems > 0" class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] rounded-full h-5 w-5 flex items-center justify-center font-bold shadow-sm">
                {{ cart.totalItems }}
            </span>
        </button>

        <div class="h-8 w-px bg-gray-200"></div>

        <div v-if="canLogin" class="flex items-center space-x-4">
            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-gray-700 font-bold hover:text-indigo-600 transition">
                Mi Panel
            </Link>
            <template v-else>
                <Link :href="route('login')" class="text-gray-600 hover:text-gray-900">Entrar</Link>
                <Link :href="route('register')" class="bg-indigo-600 text-white px-5 py-2 rounded-full font-bold hover:bg-indigo-700 transition">
                    Registrarse
                </Link>
            </template>
        </div>
    </div>
</nav>

        <header class="bg-black text-white py-16 text-center">
            <h2 class="text-4xl md:text-6xl font-extrabold mb-4 uppercase italic">Equípate como un Pro</h2>
            <p class="text-lg text-gray-400">Las mejores marcas para el deporte más hermoso del mundo.</p>
        </header>

        <div class="max-w-md mx-auto -mt-8 px-4 relative z-10">
            <div class="relative shadow-xl">
                <input 
                    v-model="search"
                    type="text" 
                    placeholder="Buscar botas, camisetas, marcas..." 
                    class="w-full pl-12 pr-12 py-4 rounded-full border-none focus:ring-2 focus:ring-indigo-500 shadow-lg text-lg"
                />
                <div class="absolute left-4 top-4.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button 
                    v-if="search" 
                    @click="search = ''" 
                    class="absolute right-4 top-4.5 text-gray-400 hover:text-indigo-600"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        <main class="max-w-7xl mx-auto py-16 px-4">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">
                    <span class="border-b-4 border-indigo-600">Novedades</span>
                </h3>
                <span class="text-sm text-gray-500">{{ products.length }} artículos encontrados</span>
            </div>
            
            <div v-if="products.length === 0" class="text-center py-20">
                <p class="text-2xl text-gray-400 italic">No encontramos productos que coincidan con "{{ search }}"</p>
                <button @click="search = ''" class="mt-4 text-indigo-600 font-bold underline">Ver todo el catálogo</button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                <div v-for="product in products" :key="product.id" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition duration-500 border border-transparent hover:border-indigo-100">
                    <div class="h-72 overflow-hidden bg-gray-50 relative">
                        <img 
                            v-if="product.image_url" 
                            :src="'/storage/' + product.image_url" 
                            class="w-full h-full object-contain p-4 transform group-hover:scale-110 transition duration-700" 
                        />
                        <div v-else class="w-full h-full flex items-center justify-center text-gray-300 uppercase font-bold tracking-widest">Sin Foto</div>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">{{ product.category?.name }}</p>
                        <h4 class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition">{{ product.name }}</h4>
                        <p class="text-sm text-gray-400 mb-6">{{ product.brand }}</p>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-2xl font-black text-gray-900">${{ product.price }}</span>
                            <button 
                                @click="addToCart(product)"
                                class="bg-black text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-indigo-600 transition shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />

        <footer class="bg-white border-t py-10 text-center text-gray-500 text-sm">
            &copy; 2026 TIENDA FÚTBOL. Todos los derechos reservados.
        </footer>
    </div> </template>