<template>
    <div v-if="authUserStore.user.name !== ''" class="wrapper" id="app">
        <AppNavbar />
        <SidebarLeft/>
        <div class="content-wrapper">
            <router-view></router-view>
        </div>
        <AppFooter />
    </div>
    <div v-else class="login-page">
        <router-view></router-view>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import AppNavbar from './components/AppNavbar.vue';
import SidebarLeft from './components/SidebarLeft.vue';
import AppFooter from './components/AppFooter.vue';
import { useAuthUserStore } from './stores/AuthUserStore';

const authUserStore = useAuthUserStore();

</script>