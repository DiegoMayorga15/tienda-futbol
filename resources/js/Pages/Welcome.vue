<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

// Props que vienen de Laravel
defineProps({
    products: Array,
});

const isCartOpen = ref(false);

const addToCart = (product) => {
    cart.add(product);
    isCartOpen.value = true;
};

// Variable para el texto infinito
const marqueeText = "PASIÓN POR EL FÚTBOL • DOMINA EL JUEGO • ESTILO AUDAZ • RENDIMIENTO PRO • ";
</script>

<template>
    <Head title="Inicio" />

    <div class="min-h-screen bg-background text-white font-sans selection:bg-primary selection:text-white">
        
        <Navbar @openCart="isCartOpen = true" />

        <section class="relative h-[85vh] w-full overflow-hidden flex items-end justify-center md:items-center">
            <div 
                class="absolute inset-0 z-0 bg-cover bg-center bg-no-repeat grayscale-[30%] scale-105"
                style="background-image: url('https://images.unsplash.com/photo-1518091043644-c1d4457512c6?q=80&w=2831&auto=format&fit=crop')"
            ></div>
            <div class="absolute inset-0 z-10 bg-gradient-to-t from-background via-background/40 to-transparent opacity-90"></div>
            
            <div class="relative z-20 flex flex-col items-center text-center px-4 pb-20 md:pb-0 w-full max-w-[1200px]">
                <div class="flex flex-col gap-2 mb-8">
                    <h2 class="font-display text-primary text-sm md:text-base font-bold tracking-[0.2em] uppercase mb-2">Nueva Colección 2026</h2>
                    <h1 class="font-display text-6xl md:text-8xl lg:text-[10rem] font-black text-white leading-[0.9] tracking-tighter uppercase italic">
                        DOMINA<br/>LA CANCHA
                    </h1>
                </div>
                <button 
                    class="group relative inline-flex items-center justify-center px-8 py-4 bg-primary text-white font-display font-bold text-lg uppercase tracking-wider overflow-hidden rounded-lg shadow-[0_0_20px_rgba(230,0,0,0.4)] hover:shadow-[0_0_40px_rgba(230,0,0,0.6)] transition-all duration-300 transform hover:-translate-y-1"
                >
                    <span class="relative z-10 flex items-center gap-2">
                        COMPRAR AHORA
                        <span class="material-symbols-outlined text-[20px] transition-transform group-hover:translate-x-1">arrow_forward</span>
                    </span>
                </button>
            </div>
        </section>

        <div class="bg-primary border-y border-white/10 overflow-hidden relative z-20">
            <div class="flex animate-marquee whitespace-nowrap">
                <span 
                    v-for="n in 4" 
                    :key="n"
                    class="text-white font-display font-black text-xl px-4 uppercase italic"
                >
                    {{ marqueeText }}
                </span>
            </div>
        </div>

        <section class="py-20 bg-background px-6">
            <div class="max-w-[1440px] mx-auto">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
                    <h2 class="text-white font-display text-4xl font-bold uppercase">Tendencias en Cancha</h2>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <Link 
                        v-for="product in products" 
                        :key="product.id" 
                        :href="route('product.show', product.id)"
                        class="group cursor-pointer"
                    >
                        <div class="aspect-[4/5] bg-surface rounded-xl overflow-hidden relative mb-4 border border-white/5">
                            <div class="absolute top-3 left-3 z-10 bg-primary text-white text-[10px] font-bold px-2 py-1 uppercase tracking-wider rounded-sm">
                                {{ product.category?.name || 'Pro' }}
                            </div>
                            
                            <img 
                                :src="'/storage/' + product.image_url" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
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
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-white font-display font-bold text-lg uppercase group-hover:text-primary transition-colors">{{ product.name }}</h3>
                                <p class="text-gray-500 text-sm mt-1">{{ product.brand }}</p>
                            </div>
                            <span class="text-white font-bold font-display text-lg">${{ product.price }}</span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <footer class="bg-background border-t border-white/10 pt-20 pb-10 px-6">
            <div class="mx-auto max-w-[1440px] text-center md:text-left">
                 <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <span class="font-display text-2xl font-black text-white italic">TIENDA<span class="text-primary">FÚTBOL</span></span>
                        <p class="text-gray-500 mt-2 text-sm max-w-xs">Equipamiento profesional para los que viven el juego.</p>
                    </div>
                    <p class="text-gray-600 text-xs uppercase tracking-wider">© 2026 TODOS LOS DERECHOS RESERVADOS.</p>
                 </div>
            </div>
        </footer>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>

<style>
/* Animación Marquee */
@keyframes marquee {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.animate-marquee {
  display: flex;
  width: fit-content;
  animation: marquee 40s linear infinite;
  will-change: transform;
}
</style>