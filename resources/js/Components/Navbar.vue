<script setup>
import { Link } from '@inertiajs/vue3';
import apiClient from "@/axios.js";
import{ref,onMounted} from "vue";

const isAuthenticated =ref(false);

//check if user is logged in
onMounted(()=>{
  const token = localStorage.getItem("access_token");
  isAuthenticated.value=!!token;//!! is used for negating 
});
//Handle logout
const handleLogout = () => {
  localStorage.removeItem("access_token");
  localStorage.removeItem("cartItems");
  delete apiCliet.defaults.headers.common["Authorization"];
  isAuthenticated.value=false;
  window.location.href="/";//refresh huney milauna baki xa
}



</script>

<template>
  <nav class="p-4 bg-blue-600 text-white flex justify-between items-center">
    <div class="text-lg font-bold">ECOM</div>
    <div class="space-x-8">
      <Link href="/" class="hover:text-blue-200">Home</Link>
      <Link href="/products" class="hover:text-blue-200">Products</Link>
      <Link href="/cart" class="hover:text-blue-200">Cart</Link>
      <Link href="/admin" class="hover:text-blue-200">Admin</Link>
      

      <template v-if="!isAuthenticated">
      <Link href="/login" class="hover:text-blue-200">Login</Link>
      <Link href="/register" class="hover:text-blue-200">Register</Link>
      </template>

      <template v-else>
      <Link href="/My-orders" class="hover:text-blue-200">MyOrders</Link>
      <button
      @click="handleLogout"
      class="bg-red-500 px-3 py-1 rounded-lg hover:bg-red-600 transition">
      Logout </button>
      </template>

    </div>
  </nav>
</template>
