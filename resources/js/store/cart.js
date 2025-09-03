export const cart = {
  namespaced: true,
  state: () => ({
    cartItems: JSON.parse(localStorage.getItem('cartItems')) || [], // Load from localStorage on initializatio
  }),

  mutations: {
    ADD_ITEM(state, product) {
      // Find if product already exists in cart
      const existing = state.cartItems.find((item) => item.id === product.id);

      if (existing) {
        // Increment quantity if product already exists
        existing.quantity += 1;
      } else {
        // Add new product with quantity 1
        state.cartItems.push({ ...product, quantity: 1 });
      }

      // ✅ SAVE TO LOCALSTORAGE AFTER EVERY CHANGE
      localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
      console.log("Cart after ADD_ITEM:", JSON.parse(JSON.stringify(state.cartItems)));
    },

    REMOVE_ITEM(state, productId) {
      state.cartItems = state.cartItems.filter((item) => item.id !== productId);
      // ✅ SAVE TO LOCALSTORAGE AFTER REMOVAL
      localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
    },

    UPDATE_QUANTITY(state, { productId, quantity }) {
      const item = state.cartItems.find((i) => i.id === productId);
      if (item) {
        item.quantity = quantity;
        // ✅ SAVE TO LOCALSTORAGE AFTER QUANTITY UPDATE
        localStorage.setItem('cartItems', JSON.stringify(state.cartItems));
      }
    },

    CLEAR_CART(state) {
      state.cartItems = [];
      // ✅ REMOVE FROM LOCALSTORAGE WHEN CART IS CLEARED
      localStorage.removeItem('cartItems');
    },
  },

  actions: {
    addItem({ commit }, product) {
      commit("ADD_ITEM", product);
    },

    removeItem({ commit }, productId) {
      commit("REMOVE_ITEM", productId);
    },

    updateQuantity({ commit }, payload) {
      commit("UPDATE_QUANTITY", payload);
    },

    clearCart({ commit }) {
      commit("CLEAR_CART");
    },
    
    // ✅ Optional: Action to load cart from localStorage (if needed)
    loadCartFromStorage({ state }) {
      const savedCart = localStorage.getItem('cartItems');
      if (savedCart) {
        state.cartItems = JSON.parse(savedCart);
      }
    }
  },

  getters: {
    totalItems: (state) =>
      state.cartItems.reduce((total, item) => total + item.quantity, 0),

    totalPrice: (state) =>
      state.cartItems.reduce((total, item) => total + item.price * item.quantity, 0),

    cartItemCount: (state) => state.cartItems.length,
  },
};