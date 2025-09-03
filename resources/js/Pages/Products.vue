<template>
  <Navbar />
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Products</h1>

    <div v-if="loading" class="text-gray-500">Loading...</div>
    <div v-else>
      <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-gray-100 text-left">
            <th class="py-3 px-6 font-medium text-gray-700">#</th>
            <th class="py-3 px-6 font-medium text-gray-700">Name</th>
            <th class="py-3 px-6 font-medium text-gray-700">Price</th>
            <th class="py-3 px-6 font-medium text-gray-700">Stock</th>
            <th class="py-3 px-6 font-medium text-gray-700">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(product, index) in products" :key="product.id" class="border-b hover:bg-gray-50">
            <td class="py-3 px-6">{{ index + 1 }}</td>
            <td class="py-3 px-6">{{ product.name }}</td>
            <td class="py-3 px-6">₨ {{ Number(product.price).toLocaleString() }}</td>
            <td class="py-3 px-6">{{ product.stock }}</td>
            <td class="py-3 px-6 flex items-center space-x-2">
              
              <!-- If product is in cart, show + and - -->
              <template v-if="isInCart(product.id)">
                <button
                  @click="decrease(product)"
                  class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition duration-200"
                >
                  -
                </button>
                <span>{{ getQuantity(product.id) }}</span>
                <button
                  @click="increase(product)"
                  class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition duration-200"
                >
                  +
                </button>
              </template>

              <!-- If product not in cart, show Add button -->
              <template v-else>
                <button
                  @click="addToCart(product)"
                  class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200"
                >
                  Add to Cart
                </button>
              </template>

            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="errorMessage" class="mt-4 text-red-600">
      {{ errorMessage }}
    </div>
  </div>
</template>

<script>
import apiClient from '../axios.js';
import Navbar from '@/Components/Navbar.vue';
import {useToast} from "vue-toastification";

export default {
  components: { Navbar },
  data() {
    return {
      products: [],
      loading: true,
      errorMessage: '',
    };
  },
  setup(){
    const toast = useToast();//Initialize toast
    return{ toast };
      },
  methods: {
    async fetchProducts() {
      this.loading = true;
      this.errorMessage = '';
      try {
        const response = await apiClient.get('/allproducts');
        this.products = response.data.data || response.data;
      } catch (err) {
        console.error('Error fetching products:', err.response ? err.response.data : err);
        this.errorMessage = 'Failed to load products. Check console for details.';
      } finally {
        this.loading = false;
      }
    },

    addToCart(product) {
      this.$store.dispatch('cart/addItem', product);
      //show toast
      this.toast.success(`${product.name} added to cart!`)
    },

    increase(product) {
      const currentQty = this.getQuantity(product.id);
      if (currentQty < product.stock) {
        this.$store.dispatch('cart/updateQuantity', {
          productId: product.id,
          quantity: currentQty + 1,
        });
      }
    },

    decrease(product) {
      const currentQty = this.getQuantity(product.id);
      if (currentQty > 1) {
        this.$store.dispatch('cart/updateQuantity', {
          productId: product.id,
          quantity: currentQty - 1,
        });
      } else if (currentQty === 1) {
        this.$store.dispatch('cart/removeItem', product.id);
      }
    },

    isInCart(productId) {
      return this.$store.state.cart.cartItems.some(item => item.id === productId);
    },

    getQuantity(productId) {
      const item = this.$store.state.cart.cartItems.find(item => item.id === productId);
      return item ? item.quantity : 0;
    },
  },
  mounted() {
    this.fetchProducts();
  },
};
</script>
