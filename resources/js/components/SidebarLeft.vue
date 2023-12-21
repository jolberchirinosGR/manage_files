<template>
    <aside class="main-sidebar sidebar-light-primary elevation-4">
        <div class="sidebar-wrapper">
            <a class="brand-link">
                <img src="../../../public/logo.png"  alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3">
                <h4 class="brand-text">Salamanca</h4>
            </a>

            <div class="sidebar">                
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <br>
                        
                        <!-- Archivos -->
                        <li class="nav-item">
                            <router-link to="/" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-folder-open"></i>
                            <p>Archivos</p>
                            </router-link>
                        </li>

                        <!-- Usuarios -->
                        <li class="nav-item" v-if="acceptRol">
                            <router-link to="/users" active-class="active" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Usuarios</p>
                            </router-link>
                        </li>

                        <br>

                        <!-- Cerra Session -->
                        <li class="nav-item">
                            <form class="nav-link">
                                <a href="#" @click.prevent="logout">
                                    <i class="nav-icon fas fa-sign-out-alt"></i>
                                    <p>
                                        Cerrar Sesión
                                    </p>
                                </a>
                            </form>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </aside>
</template>

<style>
/* Cambia el color de fondo para .nav-link.active */
.nav-link.active {
    background-color: #273746 !important;  /* Agrega !important para asegurar la prioridad del estilo */
}
</style>

<script setup>
import { useAuthUserStore } from '../stores/AuthUserStore';
import { useRouter } from 'vue-router';

const router = useRouter();
const authUserStore = useAuthUserStore();
const acceptRol = authUserStore.user.role.name === 'Admin' ? true : false;

const logout = () => {
    axios.post('/logout')
    .then((response) => {
        authUserStore.user.name = '';
        router.push('/login');
    });
};


</script>

