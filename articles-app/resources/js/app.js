import './bootstrap';
import { createApp } from 'vue';
import ListArticles from './components/ListArticles.vue';
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp({});

const toastOptions = {
    position: "top-right",
    timeout: 3000,
    closeOnClick: true,
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};

app.use(Toast, toastOptions);
app.component('list-articles', ListArticles);

app.mount('#app');
