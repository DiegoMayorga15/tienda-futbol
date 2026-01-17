import { reactive } from 'vue';

export const cart = reactive({
    items: JSON.parse(localStorage.getItem('cart')) || [],
    
    add(product) {
        const item = this.items.find(i => i.id === product.id);
        if (item) {
            item.quantity++;
        } else {
            this.items.push({ ...product, quantity: 1 });
        }
        this.save();
    },

    remove(productId) {
        this.items = this.items.filter(i => i.id !== productId);
        this.save();
    },

    save() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    },

    get totalItems() {
        return this.items.reduce((sum, item) => sum + item.quantity, 0);
    }
});