<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <Navbar />

    <div class="flex flex-1 items-center justify-center px-4">
      <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">
          Login to Your Account
        </h2>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
              Email
            </label>
            <input
              v-model="form.email"
              type="email"
              id="email"
              required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Enter your email"
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700">
              Password
            </label>
            <input
              v-model="form.password"
              type="password"
              id="password"
              required
              class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
              placeholder="Enter your password"
            />
          </div>

          <!-- Error Message -->
          <p v-if="error" class="text-red-500 text-sm text-center">
            {{ error }}
          </p>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 px-4 bg-blue-600 text-white font-semibold rounded-xl shadow-md hover:bg-blue-700 transition disabled:opacity-50"
          >
            {{ loading ? 'Logging in...' : 'Login' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '../../axios.js';
import { ref } from 'vue';
import Navbar from "@/Components/Navbar.vue";

export default {
  components: {
    Navbar
  },
  setup() {
    const form = ref({
      email: '',
      password: ''
    });
    const loading = ref(false);
    const error = ref('');

    const handleLogin = async () => {
      loading.value = true;
      error.value = '';

      try {
        const response = await apiClient.post('/login', form.value);
        const { token, user, role } = response.data;

        localStorage.setItem('access_token', token);
        apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        console.log('Login successful!', user);

        window.location.href = '/dashboard';
      } catch (err) {
        console.error('Login error:', err);
        error.value = err.response?.data?.message || 'Login failed';
      } finally {
        loading.value = false;
      }
    };

    return {
      form,
      loading,
      error,
      handleLogin
    };
  }
};
</script>
