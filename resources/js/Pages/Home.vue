<template>
  <div>
    <!-- Use the Navbar component here -->
    <Navbar />

    <div class="p-6">
      <h1 class="text-2xl font-bold">Welcome to Home Page</h1>
      <p>This is the main content of the home page.</p>
    </div>
  </div>
</template>

<script>
import Navbar from "@/Components/Navbar.vue"; // Adjust path as needed

export default {
  components: {
    Navbar
  }
};
</script>
