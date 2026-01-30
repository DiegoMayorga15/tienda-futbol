<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import CartDrawer from '@/Components/CartDrawer.vue';

const isCartOpen = ref(false);
</script>

<template>
    <Head title="Mi Cuenta" />

    <div class="min-h-screen bg-gray-50 font-sans">
        <Navbar @openCart="isCartOpen = true" />

        <div class="pt-32 pb-12 max-w-7xl mx-auto px-6">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 text-gray-900 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-black italic uppercase tracking-tighter mb-1">
                            Hola, <span class="text-primary">{{ $page.props.auth.user.name }}</span>
                        </h2>
                        <p class="text-sm text-gray-500">Bienvenido a tu panel de cliente.</p>
                    </div>
                    
                    <Link v-if="$page.props.auth.user.email === 'admin@dports.com'" 
                          :href="route('admin.dashboard')" 
                          class="bg-black text-white text-xs font-bold uppercase px-6 py-3 hover:bg-primary transition-colors">
                        Ir al Panel Admin
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-4">shopping_bag</span>
                    <h3 class="font-bold uppercase text-lg mb-2">Mis Pedidos</h3>
                    <p class="text-sm text-gray-500 mb-4">Revisa el estado de tus compras recientes.</p>
                    <button class="text-xs font-black uppercase text-primary hover:underline">Ver Historial</button>
                </div>

                <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-4">location_on</span>
                    <h3 class="font-bold uppercase text-lg mb-2">Dirección de Envío</h3>
                    <p class="text-sm text-gray-500 mb-4">Actualiza donde recibes tus productos.</p>
                    <Link :href="route('profile.edit')" class="text-xs font-black uppercase text-primary hover:underline">Editar Perfil</Link>
                </div>

                <div class="bg-white p-6 shadow-sm rounded-lg border border-gray-100">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-4">support_agent</span>
                    <h3 class="font-bold uppercase text-lg mb-2">Ayuda</h3>
                    <p class="text-sm text-gray-500 mb-4">¿Tienes problemas con un pedido?</p>
                    <button class="text-xs font-black uppercase text-primary hover:underline">Contactar</button>
                </div>
            </div>
        </div>
        
        <CartDrawer :isOpen="isCartOpen" @close="isCartOpen = false" />
    </div>
</template>