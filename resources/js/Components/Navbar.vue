<script setup>
import { Link } from '@inertiajs/vue3';
import { cart } from '@/cartStore.js';

// Recibimos una prop para saber si abrimos el carrito (igual que antes)
defineEmits(['openCart']);
</script>

<template>
    <header class="fixed top-0 z-50 w-full bg-background/95 backdrop-blur-md border-b border-white/10">
        <div class="mx-auto max-w-[1440px] px-6 py-4 flex items-center justify-between">
            <Link :href="route('welcome')" class="flex items-center gap-3 group">
                
                <img 
                    src="/img/logo-velocity.png" 
                    alt="Velocity Strikers" 
                    class="h-12 w-auto object-contain transition-transform duration-300 group-hover:scale-110"
                />
            </Link>
            
            <nav class="hidden md:flex items-center gap-10">
                <Link :href="route('welcome')" class="text-sm font-bold text-white hover:text-primary tracking-widest transition-colors duration-200">HOMBRE</Link>
                <Link :href="route('welcome')" class="text-sm font-bold text-white hover:text-primary tracking-widest transition-colors duration-200">MUJER</Link>
                <Link :href="route('welcome')" class="text-sm font-bold text-primary hover:text-white tracking-widest transition-colors duration-200">OFERTAS</Link>
            </nav>

            <div class="flex items-center gap-6">
                <div class="hidden lg:flex relative group">
                    <input 
                        type="text" 
                        class="bg-transparent border-b border-white/30 text-white placeholder-gray-500 py-1 pl-1 pr-8 text-sm focus:outline-none focus:border-primary transition-all w-32 group-hover:w-48 font-display uppercase tracking-wider focus:ring-0"
                        placeholder="BUSCAR"
                    />
                    <span class="material-symbols-outlined absolute right-0 top-1 text-white text-[20px]">search</span>
                </div>

                <button @click="$emit('openCart')" class="text-white hover:text-primary transition-colors relative group">
                    <span class="material-symbols-outlined">shopping_bag</span>
                    <span v-if="cart.totalItems > 0" class="absolute -top-1 -right-1 bg-primary text-white text-[10px] font-bold px-1 rounded-full">
                        {{ cart.totalItems }}
                    </span>
                </button>

                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-white hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">person</span>
                </Link>
                <Link v-else :href="route('login')" class="text-white hover:text-primary transition-colors text-sm font-bold uppercase tracking-widest">
                    Entrar
                </Link>
            </div>
        </div>
    </header>
</template>