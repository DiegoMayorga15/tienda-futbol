<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { cart } from '@/cartStore.js';

const emit = defineEmits(['openCart']);
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

// --- OBTENER CATEGORÍAS DE LA BASE DE DATOS ---
const page = usePage();
const categories = computed(() => page.props.categories || []);
const user = computed(() => page.props.auth.user);
const cartCount = computed(() => cart.items.reduce((total, item) => total + item.quantity, 0));

// Detectar Scroll para compactar el menú
const handleScroll = () => {
    isScrolled.value = window.scrollY > 100;
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));
</script>

<template>
    <header 
        class="fixed top-0 left-0 w-full z-50 bg-white transition-all duration-300 shadow-sm"
        :class="{ '-translate-y-[40px]': isScrolled }"
    >
        <div class="bg-black text-white py-2 px-6 text-[10px] md:text-xs font-bold uppercase tracking-widest flex justify-between items-center h-[40px]">
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">share</span> IG
                </a>
                <a href="#" class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">thumb_up</span> FB
                </a>
                <a href="#" class="hover:text-primary transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">music_note</span> TIKTOK
                </a>
            </div>

            <div class="flex items-center gap-4">
                 <Link :href="user ? route('dashboard') : route('login')" class="hover:text-primary transition-colors">
                    {{ user ? 'MI CUENTA' : 'INICIAR SESIÓN' }}
                 </Link>
            </div>
        </div>

        <div class="flex flex-col items-center justify-center py-6 border-b border-gray-100 transition-all duration-300 overflow-hidden"
             :class="isScrolled ? 'h-0 py-0 opacity-0' : 'h-auto opacity-100'">
            
            <Link :href="route('welcome')" class="group text-center">
                <h1 class="font-display font-black text-5xl italic tracking-tighter text-black mb-0 leading-none group-hover:scale-105 transition-transform">
                    D<span class="text-primary">PORTS</span>
                </h1>
                <p class="text-[10px] font-bold uppercase tracking-[0.6em] text-gray-400 group-hover:text-black transition-colors">
                    Tienda Deportiva
                </p>
            </Link>
        </div>

        <div class="border-b border-gray-200 bg-white/95 backdrop-blur-md relative z-40">
            <div class="max-w-[1600px] mx-auto px-4">
                <div class="flex items-center justify-between h-16">
                    
                    <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2">
                        <span class="material-symbols-outlined text-3xl">menu</span>
                    </button>

                    <Link :href="route('welcome')" 
                          class="lg:hidden font-black italic text-xl transition-opacity duration-300"
                          :class="isScrolled ? 'opacity-100' : 'opacity-0'">
                        D<span class="text-primary">PORTS</span>
                    </Link>

                    <nav class="hidden lg:flex items-center gap-8 w-full justify-center relative z-50">
                        <Link :href="route('welcome')" class="nav-item whitespace-nowrap">Inicio</Link>
                        
                        <div 
                            v-for="category in categories" 
                            :key="category.id"
                            class="group relative flex-shrink-0 py-4" 
                        >
                            <Link :href="route('catalog.index')" class="nav-item whitespace-nowrap flex items-center gap-1">
                                {{ category.name }}
                                <span v-if="category.children && category.children.length > 0" class="material-symbols-outlined text-[10px] opacity-50 group-hover:rotate-180 transition-transform">expand_more</span>
                            </Link>

                            <div v-if="category.children && category.children.length > 0" 
                                 class="absolute top-[80%] left-1/2 -translate-x-1/2 pt-4 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:-translate-y-1 z-50">
                                <div class="bg-white border-t-4 border-primary shadow-2xl rounded-sm overflow-hidden min-w-[200px]">
                                    <Link 
                                        v-for="child in category.children" 
                                        :key="child.id"
                                        :href="route('catalog.index')" 
                                        class="block px-6 py-3 text-xs font-bold uppercase text-gray-500 hover:text-white hover:bg-black hover:pl-8 transition-all duration-200 border-b border-gray-50 last:border-0"
                                    >
                                        {{ child.name }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </nav>

                    <div class="flex items-center gap-2 pl-4 border-l border-gray-100 ml-4 flex-shrink-0">
                        <button class="hidden sm:flex items-center justify-center size-10 hover:bg-gray-50 rounded-full transition-colors">
                            <span class="material-symbols-outlined text-gray-600">search</span>
                        </button>
                        <button @click="$emit('openCart')" class="relative flex items-center justify-center size-10 hover:bg-black group rounded-full transition-colors">
                            <span class="material-symbols-outlined text-gray-600 group-hover:text-white">shopping_bag</span>
                            <span v-if="cartCount > 0" class="absolute -top-1 -right-1 bg-primary text-white text-[9px] font-black size-4 flex items-center justify-center rounded-full">
                                {{ cartCount }}
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition duration-300 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div v-if="isMobileMenuOpen" class="fixed inset-0 z-[60] flex lg:hidden">
                <div class="w-[80%] max-w-[300px] h-full bg-white shadow-2xl overflow-y-auto">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h2 class="font-black italic text-2xl">D<span class="text-primary">PORTS</span></h2>
                        <button @click="isMobileMenuOpen = false">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                    
                    <div class="flex flex-col p-6 gap-4">
                        <Link :href="route('welcome')" class="mobile-link">Inicio</Link>
                        
                        <div v-for="category in categories" :key="category.id" class="space-y-2">
                            <div class="font-bold text-sm uppercase text-gray-400 tracking-widest">{{ category.name }}</div>
                            <div class="pl-4 border-l-2 border-primary/20 flex flex-col gap-2">
                                <Link v-if="category.children.length === 0" :href="route('catalog.index')" class="text-black font-bold uppercase text-sm">Ver Todo</Link>
                                <Link 
                                    v-for="child in category.children" 
                                    :key="child.id"
                                    :href="route('catalog.index')"
                                    class="text-gray-600 font-medium text-sm uppercase hover:text-primary"
                                >
                                    {{ child.name }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 bg-black/50" @click="isMobileMenuOpen = false"></div>
            </div>
        </Transition>
    </header>
</template>

<style scoped>
.nav-item {
    @apply px-4 py-2 text-[11px] font-extrabold uppercase tracking-[0.1em] text-gray-600 hover:text-black transition-colors hover:bg-gray-50 rounded-sm cursor-pointer;
}
.mobile-link {
    @apply text-lg font-black uppercase italic text-black hover:text-primary;
}
</style>