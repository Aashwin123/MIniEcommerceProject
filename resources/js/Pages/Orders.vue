<template>
  <div>
    <h1>Products</h1>
    <div v-if="loading">Loading...</div>
    <div v-else class="products">
      <div v-for="product in products" :key="product.id" class="product-card">
        <h3>{{ product.name }}</h3>
        <p>{{ product.description }}</p>
        <p>Price: {{ product.price }}</p>
        <button @click="addToCart(product)">Add to Cart</button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "Product",
  data() {
    return {
      products: [],
      loading: true,
    };
  },
  computed: {
    ...mapState("cart", ["cartItems"]),
  },
  methods: {
    ...mapActions("cart", ["addItem"]),
    fetchProducts() {
      fetch("/api/allproducts")
        .then((res) => res.json())
        .then((res) => {
          this.products = res.data;
          this.loading = false;
        });
    },
    addToCart(product) {
      this.addItem(product); // calls vuex action
      alert(`${product.name} added to cart`);
    },
  },
  mounted() {
    this.fetchProducts();
  },
};
</script>

<style>
.products {
  display: flex;
  flex-wrap: wrap;
}
.product-card {
  border: 1px solid #ccc;
  padding: 10px;
  margin: 5px;
  width: 200px;
}
</style>
