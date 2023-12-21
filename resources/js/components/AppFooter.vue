<template>
    <footer class="main-footer">

        <div class="float-right d-none d-sm-inline">
            {{ authUserStore.user.name }}
            <i v-if="authUserStore.user.role.name === 'Admin'" class="fas fa-user-secret" style="color: #273746 !important;"></i>
            <i v-else class="fas fa-user" style="color: #273746 !important;;"></i>

        </div>

        <strong>Grupo Ruiz &copy; 2023. </strong>Todos los derechos reservados.
    </footer>
</template>

<script setup>
    import { useAuthUserStore } from '../stores/AuthUserStore';

    const authUserStore = useAuthUserStore();
</script>
