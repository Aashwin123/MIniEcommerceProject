<template>
<Navbar />
  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold mb-6 text-blue-700">My Orders</h1>

    <div v-if="!order.length" class="text-gray-500 text-lg">No orders found.</div>

    <div v-else class="overflow-x-auto">
      <table class="min-w-full bg-white shadow-lg rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-blue-100 text-left">
            <th class="py-3 px-6 font-semibold text-blue-700">Order ID</th>
            <th class="py-3 px-6 font-semibold text-blue-700">Status</th>
            <th class="py-3 px-6 font-semibold text-blue-700">Total</th>
            <th class="py-3 px-6 font-semibold text-blue-700">Items</th>
            <th class="py-3 px-6 font-semibold text-blue-700">Date</th>
          </tr>
        </thead>

        <tbody>
          <tr
            v-for="o in order"
            :key="o.id"
            class="border-b hover:bg-blue-50 transition duration-200"
          >
            <td class="py-3 px-6 font-medium text-gray-800">{{ o.id }}</td>

            <!-- Status Badge -->
            <td class="py-3 px-6">
              <span
                :class="{
                  'bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold': o.status === 'pending',
                  'bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold': o.status === 'completed',
                  'bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold': o.status === 'canceled'
                }"
              >
                {{ o.status }}
              </span>
            </td>

            <td class="py-3 px-6 font-medium text-gray-800">₨ {{ Number(o.total).toLocaleString() }}</td>

            <!-- Items List -->
            <td class="py-3 px-6">
              <ul class="list-disc pl-4 space-y-1">
                <li v-for="item in o.items" :key="item.id">
                  Product ID: <span class="font-semibold text-blue-700">{{ item.product_id }}</span>, 
                  Qty: {{ item.quantity }}, 
                  ₨ {{ Number(item.price).toLocaleString() }}
                </li>
              </ul>
            </td>

            <td class="py-3 px-6 text-gray-600">
              {{ new Date(o.items[0]?.created_at || Date.now()).toLocaleDateString() }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import apiClient from '../axios.js';
import Navbar from '@/Components/Navbar.vue';

export default {
    components:{
        Navbar
    },
  data() {
    return {
      order: [],
    };
  },
  methods: {
    async fetchorders() {
      try {
        const response = await apiClient.get('/my-orders');
        this.order = response.data.data.map(o => ({
          id: o.id,
          total: o.total,
          status: o.status,
          items: o.items,
        }));
      } catch (err) {
        console.error('Fetching order error:', err.response ? err.response.data : err);
      }
    },
  },
  mounted() {
    this.fetchorders();
  },
};
</script>
