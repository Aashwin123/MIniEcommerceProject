<template>
<Navbar />
  <div class="cart">
    <h1>🛒 Your Cart</h1>

    <div v-if="cartItems.length === 0">
      <p>Your cart is empty.</p>
    </div>

    <div v-else>
      <ul>
        <li v-for="item in cartItems" :key="item.id">
          {{ item.name }} (x{{ item.quantity }}) - ${{ item.price * item.quantity }}
        </li>
      </ul>

      <h3>Total items: {{ totalItems }}</h3>
      <h3>Total price: ${{ totalPrice }}</h3>

      <!-- Checkout Button -->
      <button 
        @click="checkout" 
        :disabled="checkoutLoading"
        class="checkout-btn"
      >
        <span v-if="checkoutLoading">Processing...</span>
        <span v-else>Proceed to Checkout</span>
      </button>

      <p v-if="checkoutError" class="error">{{ checkoutError }}</p>
      <p v-if="checkoutSuccess" class="success">{{ checkoutSuccess }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useStore } from "vuex";
import apiClient from '../axios.js'; // Adjust path as needed
import Navbar from '@/Components/Navbar.vue'

const store = useStore();

const cartItems = computed(() => store.state.cart.cartItems);
const totalItems = computed(() => store.getters["cart/totalItems"]);
const totalPrice = computed(() => store.getters["cart/totalPrice"]);

const checkoutLoading = ref(false);
const checkoutError = ref('');
const checkoutSuccess = ref('');

const checkout = async () => {
  checkoutLoading.value = true;
  checkoutError.value = '';
  checkoutSuccess.value = '';

  try {
    // 1. Prepare order data from cart
    const orderData = {
      items: cartItems.value.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.price
      })),
      total_amount: totalPrice.value
    };

    console.log('Sending order:', orderData);

    // 2. Hit the API endpoint
    const response = await apiClient.post('/orders-with-items', orderData);
    
    console.log('Order successful:', response.data);
    
    // 3. Clear the cart on success
    store.dispatch('cart/clearCart');
    
    // 4. Show success message
    checkoutSuccess.value = 'Order placed successfully! Your cart has been cleared.';
    
  } catch (error) {
    console.error('Checkout error:', error);
    checkoutError.value = error.response?.data?.message || 'Failed to place order. Please try again.';
  } finally {
    checkoutLoading.value = false;
  }
};
</script>

<style scoped>
.cart {
  max-width: 600px;
  margin: auto;
  padding: 20px;
}

.checkout-btn {
  background-color: #4CAF50;
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  margin-top: 20px;
}

.checkout-btn:hover:not(:disabled) {
  background-color: #45a049;
}

.checkout-btn:disabled {
  background-color: #cccccc;
  cursor: not-allowed;
}

.error {
  color: #d32f2f;
  margin-top: 10px;
}

.success {
  color: #2e7d32;
  margin-top: 10px;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  padding: 8px;
  border-bottom: 1px solid #eee;
}
</style>