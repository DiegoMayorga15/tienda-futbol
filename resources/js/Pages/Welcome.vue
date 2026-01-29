<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';
import { cart } from '@/cartStore.js';

defineProps({ products: Array });

const isCartOpen = ref(false);
const marqueeText = "ENVÍOS A TODO EL PAÍS • CALIDAD GARANTIZADA • NUEVA COLECCIÓN 2026 • ";

const addToCart = (product) => {
    cart.add(product);
    isCartOpen.value = true;
};

// --- DATOS: CATEGORÍAS ---
const categories = [
    { name: 'Guayos Pro', image: 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&fit=crop', imageHover: 'https://images.unsplash.com/photo-1560272564-c83b66b1ad12?q=80&w=800&fit=crop' },
    { name: 'Camisetas', image: 'https://images.unsplash.com/photo-1518091043644-c1d4457512c6?q=80&w=800&fit=crop', imageHover: 'https://images.unsplash.com/photo-1577212017184-80e68a223f23?q=80&w=800&fit=crop' },
    { name: 'Balones', image: 'https://images.unsplash.com/photo-1614632537423-1e6c2e7e0aab?q=80&w=800&fit=crop', imageHover: 'https://images.unsplash.com/photo-1556056504-5c7696c4c28d?q=80&w=800&fit=crop' },
    { name: 'Urbano', image: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?q=80&w=800&fit=crop', imageHover: 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?q=80&w=800&fit=crop' },
    { name: 'Niños', image: 'https://images.unsplash.com/photo-1516567837654-2097971e3d43?q=80&w=800&fit=crop', imageHover: 'https://images.unsplash.com/photo-1526676037777-05a232554f77?q=80&w=800&fit=crop' },
    { name: 'Porteros', image: 'https://images.unsplash.com/photo-1624880357913-a8539238245b?q=80&w=800&fit=crop', imageHover: 'https://images.unsplash.com/photo-1589487391730-58f20eb2c308?q=80&w=800&fit=crop' },
];

// --- DATOS: RESEÑAS (Aumentadas para el efecto infinito) ---
const reviewsTop = [
    { name: 'Mateo L.', role: 'Delantero', text: 'El agarre en sintética es brutal. Mejoré mi arranque al 100%.', stars: 5 },
    { name: 'Valentina G.', role: 'Fan D-PORTS', text: 'La camiseta llegó súper rápido a Medellín. La tela respira muy bien.', stars: 5 },
    { name: 'Juan Pablo D.', role: 'Portero', text: 'Excelente atención por WhatsApp, me ayudaron a elegir mi talla exacta.', stars: 5 },
    { name: 'Carlos R.', role: 'DT', text: 'Compramos uniformes para todo el equipo y la calidad es top.', stars: 5 },
];

const reviewsBottom = [
    { name: 'Andrés M.', role: 'Cliente Frecuente', text: 'Ya es el tercer par que compro. La calidad siempre es impecable.', stars: 4 },
    { name: 'Sofia T.', role: 'Futsal', text: 'Los tenis de suela lisa tienen un grip increíble en madera.', stars: 5 },
    { name: 'David B.', role: 'Defensa', text: 'Son súper ligeros, no pesan nada. Ideales para los 90 minutos.', stars: 5 },
    { name: 'Camilo J.', role: 'Aficionado', text: 'El envío gratis me salvó. Llegaron en 2 días a Cali.', stars: 5 },
];

// --- LOGICA SLIDER HERO ---
const currentBgIndex = ref(0);
const backgrounds = [
    'https://images.unsplash.com/photo-1518091043644-c1d4457512c6?q=80&w=2831&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1577223625816-7546f13df25d?q=80&w=2000&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?q=80&w=2000&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1551958219-acbc608c6377?q=80&w=2000&auto=format&fit=crop'
];

let intervalId = null;
onMounted(() => {
    intervalId = setInterval(() => {
        currentBgIndex.value = (currentBgIndex.value + 1) % backgrounds.length;
    }, 6000);
});
onUnmounted(() => { if (intervalId) clearInterval(intervalId); });
</script>

<template>
    <Head title="Inicio" />

    <div class="min-h-screen bg-white text-gray-900 font-sans selection:bg-primary selection:text-white overflow-x-hidden">
        
        <Navbar @openCart="isCartOpen = true" />

        <section class="relative h-screen w-full overflow-hidden flex items-center justify-center bg-gray-100">
            <div class="absolute inset-0 z-0 pointer-events-none">
                <TransitionGroup name="fade">
                    <div v-for="(bg, index) in backgrounds" :key="bg" v-show="index === currentBgIndex"
                        class="absolute inset-0 w-full h-full overflow-hidden">
                        <div class="w-full h-full bg-cover bg-center bg-no-repeat animate-ken-burns"
                             :style="`background-image: url('${bg}')`">
                        </div>
                    </div>
                </TransitionGroup>
            </div>
            <div class="absolute inset-0 z-10 bg-black/30 pointer-events-none"></div>
            <div class="relative z-20 flex flex-col items-center text-center px-4 w-full max-w-[1400px] pb-32">
                <div data-aos="zoom-in" data-aos-delay="200">
                    <h2 class="bg-primary text-white text-sm md:text-base font-black px-8 py-2 uppercase tracking-[0.3em] mb-4 rotate-[-2deg] shadow-lg shadow-red-600/40 hover:rotate-0 transition-transform duration-300">
                        Nueva Temporada
                    </h2>
                </div>
                <h1 class="shimmer-text font-display font-black leading-[0.9] py-4 pr-6 tracking-tighter uppercase italic drop-shadow-2xl mb-8 text-[7vw]">
                    FÚTBOL<br/>PURO
                </h1>
                <div data-aos="fade-up" data-aos-delay="400" class="flex flex-col md:flex-row gap-6">
                    <Link :href="route('catalog.index')" class="group relative overflow-hidden bg-white text-black px-12 py-5 font-black uppercase tracking-widest transition-all hover:-translate-y-1 shadow-xl hover:shadow-2xl skew-x-[-10deg]">
                        <span class="relative z-10 block skew-x-[10deg] group-hover:tracking-[0.2em] transition-all">Ver Catálogo</span>
                    </Link>
                    <Link :href="route('catalog.index')" class="group relative overflow-hidden bg-primary text-white px-12 py-5 font-black uppercase tracking-widest transition-all hover:-translate-y-1 shadow-xl shadow-red-600/30 hover:shadow-red-600/50 skew-x-[-10deg]">
                        <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:animate-shine"></div>
                        <span class="relative z-10 block skew-x-[10deg] group-hover:scale-110 transition-transform">Comprar Ahora</span>
                    </Link>
                </div>
            </div>
            <div class="absolute bottom-0 w-full z-30 bg-primary border-t-4 border-black overflow-hidden py-6 shadow-2xl">
                <div class="flex w-max animate-marquee">
                    <div class="flex whitespace-nowrap"><span v-for="n in 10" :key="'a'+n" class="text-white font-black text-2xl px-12 uppercase italic tracking-wider stroke-text">{{ marqueeText }}</span></div>
                    <div class="flex whitespace-nowrap"><span v-for="n in 10" :key="'b'+n" class="text-white font-black text-2xl px-12 uppercase italic tracking-wider stroke-text">{{ marqueeText }}</span></div>
                </div>
            </div>
        </section>

        <section class="py-20 bg-gray-50 relative overflow-hidden bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-white via-gray-50 to-gray-100">
            <div class="px-8 mb-12 flex items-end justify-center text-center max-w-[1440px] mx-auto" data-aos="fade-right">
                <h2 class="text-5xl md:text-7xl font-black uppercase italic text-black leading-none relative z-10">
                    Explora <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-black relative inline-block">
                        Categorías
                        <span class="absolute -bottom-2 left-0 w-full h-6 bg-primary/10 -skew-x-12 -z-10 blur-sm"></span>
                    </span>
                </h2>
            </div>
            <div class="relative w-full overflow-hidden group">
                <div class="absolute left-0 top-0 bottom-0 w-20 bg-gradient-to-r from-gray-50 to-transparent z-20 pointer-events-none"></div>
                <div class="absolute right-0 top-0 bottom-0 w-20 bg-gradient-to-l from-gray-50 to-transparent z-20 pointer-events-none"></div>
                <div class="flex gap-8 w-max animate-scroll-left group-hover:pause-animation px-8 py-14">
                    <Link v-for="(cat, index) in [...categories, ...categories]" :key="index" :href="route('catalog.index')" class="relative flex-none w-[300px] md:w-[360px] aspect-[3/4] group/card cursor-pointer">
                        <div class="w-full h-full rounded-[3rem] overflow-hidden shadow-soft hover:shadow-lifted relative transform-gpu transition-all duration-500 hover:-translate-y-4 hover:rotate-1 bg-black">
                            <div class="absolute inset-0 bg-black overflow-hidden">
                                <img :src="cat.image" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-out group-hover/card:opacity-0">
                                <img :src="cat.imageHover" class="absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 ease-out group-hover/card:opacity-100 group-hover/card:scale-110">
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80 pointer-events-none"></div>
                            <div class="absolute bottom-0 left-0 p-10 w-full z-20 pointer-events-none">
                                <div class="h-2 w-2 bg-primary rounded-full mb-4 group-hover/card:w-12 transition-all duration-500 ease-out"></div>
                                <h3 class="text-4xl font-black uppercase italic text-white leading-[0.9] mb-2 drop-shadow-lg">{{ cat.name }}</h3>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <section class="relative py-32 overflow-hidden bg-black flex items-center justify-center text-center">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=2000&auto=format&fit=crop')] bg-cover bg-fixed opacity-40"></div>
            <div class="relative z-10 max-w-4xl px-6" data-aos="zoom-in">
                <p class="text-primary font-bold uppercase tracking-[0.5em] text-sm mb-6 animate-pulse">Tecnología Velocity</p>
                <h2 class="text-4xl md:text-7xl font-black uppercase leading-tight text-white mb-8 italic">
                    "NO JUEGUES.<br/> <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-white">DOMINA.</span>"
                </h2>
            </div>
        </section>

        <section class="py-24 bg-white px-6">
            <div class="max-w-[1440px] mx-auto">
                <div class="text-center mb-20" data-aos="fade-up">
                    <h2 class="text-5xl font-black uppercase italic text-black relative inline-block">
                        Top Sellers
                        <span class="absolute -top-6 -right-8 text-primary rotate-12 text-6xl">★</span>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-16">
                    <Link v-for="(product, index) in products" :key="product.id" :href="route('product.show', product.id)" class="group block relative" data-aos="fade-up" :data-aos-delay="index * 150" data-aos-duration="800">
                        <div class="aspect-[4/5] bg-gray-50 rounded-lg overflow-hidden relative mb-6 shadow-sm group-hover:shadow-2xl transition-all duration-500 group-hover:-translate-y-2 border border-gray-100">
                            <div class="absolute top-4 left-0 bg-black text-white text-[10px] font-bold px-4 py-1 uppercase z-10 shadow-lg">Nuevo</div>
                            <img :src="product.image_url && product.image_url !== 'null' ? '/storage/' + product.image_url : 'https://images.unsplash.com/photo-1511886929837-354d827aae26?q=80&w=800&fit=crop'" class="w-full h-full object-cover mix-blend-multiply transition-transform duration-700 group-hover:scale-110" />
                            <button @click.prevent="addToCart(product)" class="absolute bottom-4 right-4 bg-primary text-white size-12 rounded-full flex items-center justify-center shadow-lg group-hover:w-40 group-hover:rounded-lg transition-all duration-300 overflow-hidden">
                                <span class="material-symbols-outlined absolute transition-opacity duration-300 group-hover:opacity-0">shopping_bag</span>
                                <span class="text-xs font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 whitespace-nowrap">Añadir +</span>
                            </button>
                        </div>
                        <div class="text-left px-2">
                            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">{{ product.brand || 'D-PORTS' }}</p>
                            <h3 class="text-gray-900 font-black text-lg uppercase leading-tight mb-2 group-hover:text-primary transition-colors">{{ product.name }}</h3>
                            <span class="text-xl font-bold text-gray-900 border-b-2 border-primary/20 pb-1">${{ product.price.toLocaleString() }}</span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <section class="py-32 bg-gray-900 relative overflow-hidden">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/20 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="max-w-[1440px] mx-auto px-6 relative z-10 mb-16 text-center">
                <p class="text-primary font-bold uppercase tracking-[0.3em] text-xs mb-4">Comunidad D-PORTS</p>
                <h2 class="text-5xl md:text-6xl font-black uppercase italic text-white">
                    Voces del <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-red-400">Campo</span>
                </h2>
            </div>

            <div class="relative w-full overflow-hidden mb-8 group">
                <div class="flex gap-6 w-max animate-scroll-left group-hover:pause-animation px-4">
                    <div v-for="(review, index) in [...reviewsTop, ...reviewsTop, ...reviewsTop]" :key="'top-'+index" 
                         class="w-[350px] md:w-[450px] p-8 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 hover:border-primary/50 transition-colors duration-300">
                        <div class="flex text-yellow-400 mb-4 gap-1">
                            <span v-for="n in 5" :key="n" class="material-symbols-outlined text-lg" :class="n <= review.stars ? 'filled-icon' : ''">star</span>
                        </div>
                        <p class="text-gray-300 italic text-lg leading-relaxed mb-6">"{{ review.text }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center font-bold text-white text-xs">{{ review.name.charAt(0) }}</div>
                            <div>
                                <h4 class="font-bold text-white text-sm uppercase">{{ review.name }}</h4>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-primary">{{ review.role }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative w-full overflow-hidden group">
                <div class="flex gap-6 w-max animate-scroll-right group-hover:pause-animation px-4">
                    <div v-for="(review, index) in [...reviewsBottom, ...reviewsBottom, ...reviewsBottom]" :key="'bottom-'+index" 
                         class="w-[350px] md:w-[450px] p-8 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 hover:border-primary/50 transition-colors duration-300">
                        <div class="flex text-yellow-400 mb-4 gap-1">
                            <span v-for="n in 5" :key="n" class="material-symbols-outlined text-lg" :class="n <= review.stars ? 'filled-icon' : ''">star</span>
                        </div>
                        <p class="text-gray-300 italic text-lg leading-relaxed mb-6">"{{ review.text }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="size-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center font-bold text-white text-xs">{{ review.name.charAt(0) }}</div>
                            <div>
                                <h4 class="font-bold text-white text-sm uppercase">{{ review.name }}</h4>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-primary">{{ review.role }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20 px-6 bg-white overflow-hidden">
            <div class="max-w-[1440px] mx-auto bg-black text-white rounded-[2rem] relative p-12 md:p-24 flex flex-col md:flex-row items-center justify-between group overflow-hidden">
                <div class="absolute -top-20 -left-20 size-96 bg-primary/20 rounded-full blur-3xl group-hover:bg-primary/30 transition-colors duration-700"></div>
                <div class="absolute bottom-0 right-0 size-80 bg-blue-600/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 md:w-1/2 space-y-6">
                    <h2 class="text-5xl md:text-7xl font-black uppercase italic leading-none">
                        Únete al <br/><span class="text-primary">Equipo</span>
                    </h2>
                    <p class="text-gray-400 max-w-md">Recibe acceso anticipado a lanzamientos limitados y un 15% OFF en tu primera orden.</p>
                </div>
                <div class="relative z-10 md:w-1/2 w-full mt-10 md:mt-0 flex gap-0">
                    <input type="email" placeholder="TU CORREO" class="w-full bg-white/10 border-2 border-transparent focus:border-primary text-white placeholder-gray-500 px-6 py-4 font-bold outline-none rounded-l-lg transition-all" />
                    <button class="bg-primary text-white px-8 py-4 font-black uppercase tracking-widest hover:bg-white hover:text-black transition-colors rounded-r-lg">Enviar</button>
                </div>
            </div>
        </section>

        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
        
        <footer class="bg-white text-black py-16 border-t border-gray-100">
            <div class="max-w-[1440px] mx-auto px-6 text-center">
                <h3 class="font-display font-black text-3xl italic tracking-tighter mb-4 hover:tracking-widest transition-all duration-500 cursor-default">D<span class="text-primary">PORTS</span></h3>
                <p class="text-gray-400 text-xs uppercase tracking-[0.3em]">Bogotá, Colombia • Est. 2026</p>
            </div>
        </footer>
    </div>
</template>

<style>
/* --- SOMBRAS PERSONALIZADAS --- */
.shadow-soft { box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
.hover\:shadow-lifted:hover { box-shadow: 0 35px 60px -15px rgba(0, 0, 0, 0.15), 0 20px 25px -10px rgba(0, 0, 0, 0.08); }

/* --- ANIMACIONES SCROLL INFINITO --- */
@keyframes scrollLeft { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
.animate-scroll-left { animation: scrollLeft 60s linear infinite; will-change: transform; }

@keyframes scrollRight { 0% { transform: translateX(-50%); } 100% { transform: translateX(0); } }
.animate-scroll-right { animation: scrollRight 60s linear infinite; will-change: transform; }

.pause-animation:hover { animation-play-state: paused; }

/* --- ICONOS RELLENOS --- */
.material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
.filled-icon { font-variation-settings: 'FILL' 1; }

/* --- OTRAS UTILIDADES --- */
.shimmer-text {
  background: linear-gradient(110deg, #ffffff 35%, #ff4d4d 50%, #ffffff 65%);
  background-size: 200% auto;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: shimmer 3s linear infinite;
}
@keyframes shimmer { to { background-position: 200% center; } }
@keyframes marquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
.animate-marquee { display: flex; width: max-content; animation: marquee 30s linear infinite; will-change: transform; }
@keyframes kenBurns { 0% { transform: scale(1); } 100% { transform: scale(1.15); } }
.animate-ken-burns { animation: kenBurns 6s ease-out forwards; }
.fade-enter-active, .fade-leave-active { transition: opacity 1.5s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
@keyframes shine { 100% { transform: translateX(100%); } }
.group-hover\:animate-shine:hover { animation: shine 0.5s; }
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>