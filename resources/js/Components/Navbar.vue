<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { cart } from '@/cartStore.js';

const emit = defineEmits(['openCart']);
const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);

// --- ESTRUCTURA DEL MENÚ (Configuración Fácil) ---
const menuCategories = [
    {
        label: 'Ropa',
        items: [
            { name: 'Uniformes', link: '#' },
            { name: 'Camisetas Pro', link: '#' },
            { name: 'Camisetas Retro', link: '#' }
        ]
    },
    {
        label: 'Calzado',
        items: [
            { name: 'Guayos', link: '#' },
            { name: 'Torretín (Sintética)', link: '#' },
            { name: 'Futsala (Suela Lisa)', link: '#' }
        ]
    },
    {
        label: 'Accesorios',
        items: [
            { name: 'Guantes de Portero', link: '#' },
            { name: 'Balones', link: '#' },
            { name: 'Canilleras', link: '#' },
            { name: 'Petos', link: '#' }
        ]
    }
];

// Detectar Scroll
const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => window.addEventListener('scroll', handleScroll));
onUnmounted(() => window.removeEventListener('scroll', handleScroll));

const cartCount = computed(() => cart.items.reduce((total, item) => total + item.quantity, 0));
const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <nav 
        class="fixed top-0 left-0 w-full z-50 transition-all duration-300 border-b"
        :class="isScrolled 
            ? 'bg-white/95 backdrop-blur-md border-gray-200 py-3 shadow-md' 
            : 'bg-white border-transparent py-5'"
    >
        <div class="max-w-[1440px] mx-auto px-6 flex items-center justify-between">
            
            <Link :href="route('welcome')" class="group relative z-50">
                <div class="font-display font-black text-2xl md:text-3xl italic tracking-tighter text-black transition-transform group-hover:scale-105">
                    D<span class="text-primary group-hover:underline decoration-4 underline-offset-4 decoration-black">PORTS</span>
                </div>
            </Link>

            <div class="hidden lg:flex items-center gap-8">
                <Link :href="route('welcome')" class="nav-item">Inicio</Link>
                
                <div 
                    v-for="(category, index) in menuCategories" 
                    :key="index"
                    class="group relative"
                >
                    <button class="nav-item flex items-center gap-1">
                        {{ category.label }}
                        <span class="material-symbols-outlined text-sm transition-transform duration-300 group-hover:rotate-180 text-gray-400 group-hover:text-primary">expand_more</span>
                    </button>

                    <div class="absolute top-full left-1/2 -translate-x-1/2 pt-6 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform group-hover:-translate-y-1">
                        <div class="bg-white border-t-4 border-primary shadow-2xl rounded-b-lg overflow-hidden min-w-[220px] py-2">
                            <Link 
                                v-for="(item, i) in category.items" 
                                :key="i"
                                :href="route('catalog.index')" 
                                class="block px-6 py-3 text-sm font-bold uppercase text-gray-500 hover:text-white hover:bg-black hover:pl-8 transition-all duration-200"
                            >
                                {{ item.name }}
                            </Link>
                        </div>
                    </div>
                </div>

                <Link href="#" class="nav-item text-primary">Ofertas</Link>
            </div>

            <div class="flex items-center gap-4 relative z-50">
                <button class="hidden sm:flex items-center justify-center size-10 rounded-full hover:bg-gray-100 transition-colors">
                    <span class="material-symbols-outlined text-black">search</span>
                </button>

                <Link 
                    :href="user ? route('dashboard') : route('login')" 
                    class="hidden sm:flex items-center justify-center size-10 rounded-full hover:bg-gray-100 transition-colors"
                >
                    <span class="material-symbols-outlined text-black filled-icon">person</span>
                </Link>

                <button 
                    @click="$emit('openCart')" 
                    class="group relative flex items-center justify-center size-10 rounded-full hover:bg-black transition-colors"
                >
                    <span class="material-symbols-outlined text-black group-hover:text-white transition-colors">shopping_bag</span>
                    <span 
                        v-if="cartCount > 0" 
                        class="absolute -top-1 -right-1 bg-primary text-white text-[10px] font-black size-5 flex items-center justify-center rounded-full border-2 border-white animate-bounce-in"
                    >
                        {{ cartCount }}
                    </span>
                </button>

                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden flex items-center justify-center size-10">
                    <span class="material-symbols-outlined text-black text-3xl">
                        {{ isMobileMenuOpen ? 'close' : 'menu' }}
                    </span>
                </button>
            </div>
        </div>

        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div v-if="isMobileMenuOpen" class="absolute top-full left-0 w-full bg-white border-b border-gray-100 shadow-xl lg:hidden max-h-[80vh] overflow-y-auto">
                <div class="flex flex-col p-6 gap-6">
                    <Link :href="route('welcome')" class="mobile-link">Inicio</Link>
                    
                    <div v-for="(category, index) in menuCategories" :key="index" class="space-y-3">
                        <h4 class="text-sm font-black uppercase text-primary tracking-widest border-b border-gray-100 pb-2">{{ category.label }}</h4>
                        <div class="flex flex-col gap-3 pl-4 border-l-2 border-gray-100">
                            <Link 
                                v-for="item in category.items" 
                                :key="item.name" 
                                :href="route('catalog.index')" 
                                class="text-gray-600 font-bold text-sm uppercase hover:text-black"
                            >
                                {{ item.name }}
                            </Link>
                        </div>
                    </div>

                    <Link href="#" class="mobile-link text-red-600">Ofertas</Link>
                    
                    <div class="h-px w-full bg-gray-100 my-2"></div>
                    <div class="flex items-center gap-3">
                         <span class="material-symbols-outlined">person</span>
                         <Link :href="user ? route('dashboard') : route('login')" class="text-sm font-bold uppercase tracking-widest text-gray-500">
                            {{ user ? 'Mi Cuenta' : 'Iniciar Sesión' }}
                         </Link>
                    </div>
                </div>
            </div>
        </Transition>
    </nav>
</template>

<style scoped>
/* Estilo Base de Items */
.nav-item {
    @apply text-xs font-bold uppercase tracking-[0.15em] text-gray-600 hover:text-black transition-colors cursor-pointer relative py-2;
}

/* Línea roja animada al hover del padre */
.nav-item::after {
    content: '';
    @apply absolute bottom-0 left-0 w-0 h-[2px] bg-primary transition-all duration-300 ease-out;
}
.nav-item:hover::after, .group:hover .nav-item::after {
    @apply w-full;
}

/* Estilo Móvil */
.mobile-link {
    @apply text-xl font-black uppercase italic tracking-wide text-black hover:text-primary;
}

/* Animaciones */
@keyframes bounceIn {
    0% { transform: scale(0); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}
.animate-bounce-in {
    animation: bounceIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.filled-icon {
    font-variation-settings: 'FILL' 1;
}
</style>