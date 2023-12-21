//Librerias importadas
import './bootstrap';
import "bootstrap/dist/js/bootstrap.min";
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';
import App from './App.vue';
import { useAuthUserStore } from './stores/AuthUserStore';

const pinia = createPinia();
const app = createApp(App);

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

router.beforeEach(async (to, from, next) => {
    const authUserStore = useAuthUserStore();

    //Comprobar usuario logeado o no
    if (authUserStore.user.name === '' && to.path !== '/login') {
        await Promise.all([
            authUserStore.getAuthUser(),
        ]);
    }

    next();
});

app.use(pinia);
app.use(router);

app.mount('#app');
