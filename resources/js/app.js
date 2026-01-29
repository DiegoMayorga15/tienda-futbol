import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// 1. IMPORTAR AOS
import AOS from 'aos';
import 'aos/dist/aos.css'; // Importar los estilos de las animaciones

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin)
           .use(ZiggyVue)
           .mount(el);
        
        // 2. INICIAR AOS (Aquí sucede la magia)
        AOS.init({
            duration: 1000, // Duración de la animación (1 segundo)
            easing: 'ease-out-cubic', // Tipo de movimiento suave
            once: true, // Que solo se anime una vez al bajar
            offset: 50, // Que empiece un poco antes de aparecer
        });
    },
    progress: {
        color: '#e60000', // Color rojo Velocity para la barra de carga superior
    },
});