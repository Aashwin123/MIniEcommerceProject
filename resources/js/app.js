import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createStore } from 'vuex'; // Import createStore from Vuex

//import toast
import Toast,{POSITION} from"vue-toastification";
import "vue-toastification/dist/index.css"

// Import your cart module
import { cart } from './store/cart';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Create Vuex store
const store = createStore({
  modules: {    
    cart,
  },
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(store) // Add Vuex store
            .use(Toast,{
                position:POSITION.Top_RIght,
                timeout:2000,
            })
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
