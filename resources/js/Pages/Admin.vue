<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
    
    <div class="flex space-x-4 mb-6">
      <button 
        @click="fetchProducts" 
        :disabled="loading"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
      >
        {{ loading ? 'Loading...' : 'Manage Products' }}
      </button>

      <button 
        @click="fetchOrders" 
        :disabled="loadingOrders"
        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:opacity-50"
      >
        {{ loadingOrders ? 'Loading...' : 'View Orders' }}
      </button>
    </div>

    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <!-- Add Product Form -->
    <div v-if="showProducts" class="mb-6 bg-white shadow-md rounded-lg p-4">
      <h2 class="text-xl font-semibold mb-4">Add New Product</h2>
      <div class="space-y-2 max-w-md">
        <input v-model="newProduct.name" placeholder="Name" class="w-full border px-2 py-1 rounded" />
        <input v-model="newProduct.description" placeholder="Description" class="w-full border px-2 py-1 rounded" />
        <input v-model="newProduct.price" placeholder="Price" type="number" class="w-full border px-2 py-1 rounded" />
        <input v-model="newProduct.stock" placeholder="Stock" type="number" class="w-full border px-2 py-1 rounded" />
        <button @click="addProduct" :disabled="addingProduct" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50">
          {{ addingProduct ? 'Adding...' : 'Add Product' }}
        </button>
      </div>
    </div>

    <!-- Products Table -->
    <div v-if="showProducts && products.length > 0" class="bg-white shadow-md rounded-lg overflow-hidden mb-6">
      <h2 class="text-xl font-semibold p-4 bg-gray-100">Products</h2>
      <table class="min-w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Name</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Price</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Description</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Stock</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
            <td class="py-4 px-6">{{ product.name }}</td>
            <td class="py-4 px-6">₨ {{ Number(product.price).toLocaleString() }}</td>
            <td class="py-4 px-6">{{ product.description }}</td>
            <td class="py-4 px-6">{{ product.stock }}</td>
            <td class="py-4 px-6">
              <button 
                @click="deleteProduct(product.id)"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 mr-2"
              >
                Delete
              </button>
              <button 
                @click="editProduct(product)"
                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
              >
                Edit
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Orders Table -->
    <div v-if="showOrders && orders.length > 0" class="bg-white shadow-md rounded-lg overflow-hidden">
      <h2 class="text-xl font-semibold p-4 bg-gray-100">Orders</h2>
      <table class="min-w-full">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Order ID</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Customer</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Email</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Total</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Status</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Date</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Items</th>
            <th class="py-3 px-6 text-left font-medium text-gray-700">Update Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
            <td class="py-4 px-6">#{{ order.id }}</td>
            <td class="py-4 px-6">{{ order.user.name }}</td>
            <td class="py-4 px-6">{{ order.user.email }}</td>
            <td class="py-4 px-6">₨ {{ Number(order.total).toLocaleString() }}</td>
            <td class="py-4 px-6">
              <span :class="{
                'bg-yellow-100 text-yellow-800': order.status === 'pending',
                'bg-green-100 text-green-800': order.status === 'delivered',
                'bg-blue-100 text-blue-800': order.status === 'processing',
                'bg-red-100 text-red-800': order.status === 'cancelled',
                'px-2 py-1 rounded-full text-xs': true
              }">
                {{ order.status }}
              </span>
            </td>
            <td class="py-4 px-6">{{ new Date(order.created_at).toLocaleDateString() }}</td>
            <td class="py-4 px-6">
              <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                {{ order.items.length }} items
              </span>
            </td>
            <td class="py-4 px-6">
              <select 
                v-model="order.status" 
                @change="updateOrderStatus(order.id, order.status)"
                class="border rounded px-2 py-1 text-sm"
                :disabled="updatingOrder === order.id"
              >
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
              </select>
              <span v-if="load" class="ml-2 text-gray-500 text-sm">Updating...</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div v-if="showProducts && products.length === 0 && !loading" class="text-gray-500 mt-4">
      No products found. Click "Manage Products" to load products.
    </div>

    <div v-if="showOrders && orders.length === 0 && !loadingOrders" class="text-gray-500 mt-4">
      No orders found. Click "View Orders" to load orders.
    </div>
  </div>
</template>

<script>
import apiClient from '../axios.js';
import { ref } from 'vue';

export default {
  setup() {
    const products = ref([]);
    const orders = ref([]);
    const loading = ref(false);
    const loadingOrders = ref(false);
    const updatingOrder = ref(null);
    const error = ref('');
    const showProducts = ref(false);
    const showOrders = ref(false);
    const load = ref(false);

    const newProduct = ref({ name: '', description: '', price: '', stock: '' });
    const addingProduct = ref(false);

    const fetchProducts = async () => {
      loading.value = true;
      error.value = '';
      showProducts.value = true;
      showOrders.value = false;
      try {
        const response = await apiClient.get('/products');
        products.value = response.data;
      } catch (err) {
        console.error('Error fetching products:', err);
        error.value = err.response?.data?.message || 'Failed to fetch products';
      } finally {
        loading.value = false;
      }
    };

    const fetchOrders = async () => {
      loadingOrders.value = true;
      error.value = '';
      showOrders.value = true;
      showProducts.value = false;
      try {
        const response = await apiClient.get('/admin-orders');
        orders.value = response.data.data;
      } catch (err) {
        console.error('Error fetching orders:', err);
        error.value = err.response?.data?.message || 'Failed to fetch orders';
      } finally {
        loadingOrders.value = false;
      }
    };

    const updateOrderStatus = async (orderId, newStatus) => {
      updatingOrder.value = orderId;
      load.value = true;
      try {
        const response = await apiClient.put(`/orders/${orderId}`, { status: newStatus });
        const updatedOrder = response.data;
        const index = orders.value.findIndex(order => order.id === orderId);
        if (index !== -1) orders.value[index] = updatedOrder;
      } catch (err) {
        console.error('Error updating order status:', err);
        alert('Failed to update order status');
        fetchOrders();
      } finally {
        updatingOrder.value = null;
        load.value = false;
      }
    };

    const deleteProduct = async (productId) => {
      if (!confirm('Are you sure you want to delete this product?')) return;
      try {
        await apiClient.delete(`/products/${productId}`);
        products.value = products.value.filter(p => p.id !== productId);
        alert('Product deleted successfully!');
      } catch (err) {
        console.error('Error deleting product:', err);
        alert('Failed to delete product');
      }
    };

    const editProduct = (product) => {
      console.log('Edit product:', product);
      alert('Edit functionality to be implemented');
    };

    const addProduct = async () => {
      addingProduct.value = true;
      try {
        const response = await apiClient.post('/products', { 
          name: newProduct.value.name,
          description: newProduct.value.description,
          price: newProduct.value.price,
          stock: newProduct.value.stock
        });
        products.value.push(response.data.data);
        alert('Product created successfully!');
        newProduct.value = { name: '', description: '', price: '', stock: '' };
      } catch (err) {
        console.error('Failed to create product:', err);
        alert(err.response?.data?.message || 'Failed to create product');
      } finally {
        addingProduct.value = false;
      }
    };

    return {
      products, orders, loading, loadingOrders, updatingOrder, error, showProducts, showOrders, load,
      fetchProducts, fetchOrders, updateOrderStatus, deleteProduct, editProduct,
      newProduct, addingProduct, addProduct
    };
  }
};
</script>
