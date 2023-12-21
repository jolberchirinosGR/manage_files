<template>
    <section>
        <div class="container py-5 h-100">
            <div class="d-flex justify-content-center h-100">
                <div class="user_card">
                    <div class="d-flex justify-content-center">
                        <div class="brand_logo_container">
                            <img src="../../../../public/logo.png" class="brand_logo" alt="Logo">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center form_container">
                        <form @submit.prevent="handleSubmit">
                            <h5 class="login-box-msg"><strong>Inicia con tus credenciales...</strong></h5>
                            <div v-if="errorMessage" class="alert alert-danger" role="alert">
                                {{ errorMessage }}
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-at"></i></span>
                                </div>
                                <input v-model="form.email" type="email" class="form-control" placeholder="Correo">
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input v-model="form.password" type="password" class="form-control" placeholder="*********">
                            </div>
                            <div class="text-center pt-1 mb-5 pb-1">
                                <button type="submit" class="btn login_btn" :disabled="loading">
                                    <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Cargando...</span>
                                    </div>
                                    <span v-else class="fas fa-sign-in-alt"></span> Iniciar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>          
</template>

<style>
    body,html {
		margin: 0;
		padding: 0;
		height: 100%;
		background: #ffffff !important;
	}
    .user_card {
    	height: 450px;
    	width: 350px;
    	margin-top: auto;
    	margin-bottom: auto;
        background: #5e86ac; /* Azul oscuro */
    	position: relative;
    	display: flex;
    	justify-content: center;
    	flex-direction: column;
    	padding: 10px;
    	box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    	-webkit-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    	-moz-box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    	border-radius: 5px;

    }
    .brand_logo_container {
    	position: absolute;
    	height: 170px;
    	width: 170px;
    	top: -75px;
    	border-radius: 50%;
        background: rgba(255, 255, 255, 0.4); /* Blanco */
    	padding: 10px;
    	text-align: center;
    }
    .brand_logo {
    	height: 150px;
    	width: 150px;
    	border-radius: 50%;
        border: none; /* Elimina el borde alrededor del logo */
    }
    .form_container {
    	margin-top: 100px;
    }
    .login_btn {
    	width: 100%;
        background: #273746 !important; /* Azul oscuro */
    	color: white !important;
    }
    .login_btn:focus {
    	box-shadow: none !important;
    	outline: 0px !important;
    }
    .login_container {
    	padding: 0 2rem;
    }
    .input-group-text {
        background: #273746 !important; /* Azul oscuro */
    	color: white !important;
    	border: 0 !important;
    	border-radius: 0.25rem 0 0 0.25rem !important;
    }
    .input_user,
    .input_pass:focus {
    	box-shadow: none !important;
    	outline: 0px !important;
    }
</style>

<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthUserStore } from '../../stores/AuthUserStore';
import { useToastr } from '../../toastr.js';

const toastr = useToastr();
const authUserStore = useAuthUserStore();
const router = useRouter();
const form = reactive({
    email: '',
    password: '',
});

const loading = ref(false);

const errorMessage = ref('');
const handleSubmit = () => {
    loading.value = true;
    errorMessage.value = '';
    axios.post('/login', form)
        .then(() => {
            router.push('/');
        })
        .catch((error) => {
            errorMessage.value = error.response.data.message;
        })
        .finally(() => {
            loading.value = false;
        });
};
</script>
