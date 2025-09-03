import { createRouter, createWebHistory } from 'vue-router'
import Products from '../Pages/Products.vue'
import Orders from '../Pages/Orders.vue'

const routes = [
  { path: '/orders', component: Orders },
  {path: '/products',component: Products},
  // ...other routes
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

