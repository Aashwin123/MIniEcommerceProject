import { createStore } from "vuex";
import { cart } from "./cart";

export default createStore({
  modules: {
    cart, // <-- make sure this is here
  },
});
