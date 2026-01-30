<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex font-sans">
        
        <aside class="w-64 bg-black text-white flex-shrink-0 hidden md:flex flex-col h-screen sticky top-0">
            <div class="p-6 border-b border-gray-800">
                <h1 class="font-display font-black text-2xl italic tracking-tighter text-white">
                    D<span class="text-primary">PORTS</span> <span class="text-xs text-gray-500 not-italic ml-1">ADMIN</span>
                </h1>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                
                <Link :href="route('admin.dashboard')" 
                      class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 hover:text-white transition-colors"
                      :class="{ 'bg-primary text-white font-bold': $page.url === '/admin' }">
                    <span class="material-symbols-outlined">dashboard</span>
                    Resumen
                </Link>

                <div class="pt-6 pb-2 px-4 text-[10px] font-black text-gray-600 uppercase tracking-widest">Gestión de Tienda</div>

                <Link :href="route('admin.categories')" 
                      class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 hover:text-white transition-colors"
                      :class="{ 'bg-primary text-white font-bold': $page.url.startsWith('/admin/categories') }">
                    <span class="material-symbols-outlined">category</span>
                    Categorías
                </Link>

                <Link :href="route('admin.products')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 hover:text-white transition-colors"
                    :class="{ 'bg-primary text-white font-bold': $page.url.startsWith('/admin/products') }">
                    <span class="material-symbols-outlined">inventory_2</span>
                    Productos
                </Link>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 hover:text-white transition-colors opacity-50 cursor-not-allowed" title="Próximamente">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Pedidos
                </a>

                <div class="pt-6 pb-2 px-4 text-[10px] font-black text-gray-600 uppercase tracking-widest">Sistema</div>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-800 text-gray-400 hover:text-white transition-colors opacity-50">
                    <span class="material-symbols-outlined">group</span>
                    Clientes
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800 bg-gray-900/50">
                <Link :href="route('welcome')" class="flex items-center gap-3 px-4 py-2 text-xs font-bold uppercase text-gray-500 hover:text-white transition-colors">
                    <span class="material-symbols-outlined text-sm">storefront</span> Volver a la Tienda
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-3 px-4 py-2 text-xs font-bold uppercase text-red-500 hover:text-red-400 transition-colors mt-1">
                    <span class="material-symbols-outlined text-sm">logout</span> Cerrar Sesión
                </Link>
            </div>
        </aside>

        <main class="flex-1 h-screen overflow-y-auto bg-gray-100 relative">
            
            <div class="bg-black text-white p-4 flex md:hidden justify-between items-center sticky top-0 z-30">
                <span class="font-black italic">DPORTS ADMIN</span>
                <button class="material-symbols-outlined">menu</button>
            </div>

            <div class="p-6 md:p-10 max-w-7xl mx-auto">
                <slot />
            </div>
        </main>
    </div>
</template>