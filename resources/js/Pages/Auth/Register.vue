<template>
<Navbar />
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
    
      <div>
      
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="name" class="sr-only">Name</label>
            <input
              v-model="form.name"
              id="name"
              name="name"
              type="text"
              
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Full Name"
            >
            <p v-if="errors.name" class="text-red-600 text-sm">{{errors.name[0]}}</p>
          </div>
          <div>
            <label for="email-address" class="sr-only">Email address</label>
            <input
              v-model="form.email"
              id="email-address"
              name="email"
              type="email"
              
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Email address"
            >
            <p v-if="errors.email" class="text-red-600 text-sm">{{errors.email[0]}}</p>
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              v-model="form.password"
              id="password"
              name="password"
              type="password"
              
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Password"
            >
           
          </div>
          <div>
            <label for="password-confirmation" class="sr-only">Confirm Password</label>
            <input
              v-model="form.password_confirmation"
              id="password-confirmation"
              name="password_confirmation"
              type="password"
              
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
              placeholder="Confirm Password"
            >
             <p v-if="errors.password" class="text-red-600 text-sm">{{errors.password[0]}}</p>
          </div>
        </div>

        

        <div>
          <button
            type="submit"
            :disabled="!isFormValid"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
          >
            <span v-if="loading">Creating account...</span>
            <span v-else>Create account</span>
          </button>
        </div>
        <p v-if="successMessage" class="text-green-600">{{successMessage}}</p>
        <p v-if="errorMessage" class="text-red-600">{{errorMessage}}</p>
        <div class="text-center">
          <router-link to="/login" class="text-indigo-600 hover:text-indigo-500 text-sm">
            Already have an account? Sign in
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref ,computed} from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '../../axios.js'; // Adjust path as needed
import Navbar from '@/Components/Navbar.vue'

const router = useRouter();



const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
});

const loading = ref(false);
const errors = ref({});

//showing success and error message
const successMessage=ref("");
const errorMessage=ref("");


const isFormValid = computed (()=>
{
  return(
    form.value.name.trim()!==''&&
    form.value.email.trim() !==''&&
    form.value.password.trim()!==''&&
    form.value.password_confirmation.trim() !==''
  );
}
);

const handleRegister = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value="";
  errorMessage.value="";
  try {
    // Simple POST request to the register endpoint
    if(form.value.password===form.value.password_confirmation)
    {
    const response = await apiClient.post('/register', form.value);
    console.log(form.value)
    
    console.log('Registration successful:', response.data);
    }


    if(response.data.success){
       successMessage.value=response.data.message;
     }
    // else{
    //   errorMessage.value="Password and confirmation donot match"
    // }
    
    
    // Option 1: Redirect to login page with success message
    // router.push({ 
    //   path: '/login', 
    //   query: { registered: 'true' } 
    // });
    
    // Option 2: Auto-login the user after registration
    // If you want to automatically log them in, you would:
    // 1. Get the token from response (if your backend returns it)
    // 2. Store it and set headers like in Login.vue
    // 3. Redirect to dashboard
    
  } catch (error) {
    console.error('Registration error:', error);
    
    if (error.response?.data?.errors) {
      // Handle Laravel validation errors
      errors.value = error.response.data.errors;
      errorMessage.value = Object.values(error.response.data.errors).flat().join(",");
    } else if (error.response?.data?.message) {
      // Handle other API errors
      
      errorMessage.value=response.data.message;
    } else {
      // Handle network errors
     // errors.value.general = 'Network error. Please try again.';
      errorMessage.value="Password and confirm_password donot match";
    }
  } finally {
    loading.value = false;
  }
};
</script>