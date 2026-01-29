import { reactive, watch } from 'vue';

// 1. Intentamos recuperar el carrito guardado del navegador
const savedCart = JSON.parse(localStorage.getItem('velocity_cart')) || [];

export const cart = reactive({
    items: savedCart,

    // AÑADIR (Con lógica de agrupación)
    add(product) {
        // Buscamos si ya existe este producto EXACTO (mismo ID y misma Talla)
        const existingItem = this.items.find(item => 
            item.id === product.id && item.selectedSize === product.selectedSize
        );

        if (existingItem) {
            // Si ya existe, solo sumamos 1 a la cantidad
            existingItem.quantity++;
        } else {
            // Si es nuevo, lo agregamos con cantidad 1
            this.items.push({ 
                ...product, 
                quantity: 1 
            });
        }
    },

    // QUITAR (La corrección importante)
    remove(itemToRemove) {
        // Filtramos la lista para dejar SOLO los que NO sean el item a borrar
        this.items = this.items.filter(item => item !== itemToRemove);
    },

    // AUMENTAR CANTIDAD (+)
    increase(item) {
        item.quantity++;
    },

    // DISMINUIR CANTIDAD (-)
    decrease(item) {
        if (item.quantity > 1) {
            item.quantity--;
        } else {
            // Si baja de 1, preguntamos o lo borramos directo
            this.remove(item);
        }
    },

    // LIMPIAR TODO
    clear() {
        this.items = [];
    }
});

// 2. Guardar automáticamente cualquier cambio en el navegador
watch(
    () => cart.items,
    (newItems) => {
        localStorage.setItem('velocity_cart', JSON.stringify(newItems));
    },
    { deep: true }
);