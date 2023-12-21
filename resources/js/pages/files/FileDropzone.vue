<template>
    <div class="card">
        <div class="card-body">
            <div id="my-dropzone" class="dropzone styled-dropzone">
                <!-- Contenedor del GIF de carga -->
                <div id="loading-indicator" class="loading-indicator" style="display: none;">
                    <img src="../../../../public/loading.gif" alt="Loading...">
                </div>
            </div>
            <br>
            <button id="submit-files-button" type="button" class="btn btn-success mx-2">
                <i class="fas fa-save"></i>
                Subir archivos
            </button>
        </div>
    </div>   
</template>

<script>
import axios from 'axios';
import Dropzone from "dropzone";
import { showSuccessMessage, showErrorMessage, showErrorGroupMessages }  from '../../sweet.js';
import { useAuthUserStore } from '../../stores/AuthUserStore';

const axiosConfig = {
    headers: {
        'Content-Type': 'multipart/form-data'
    }
};

export default {
    emits: ['upload-files'], //Eventos que se generan en este componente

    data() {
        return {
            authUserStore: null,
        };
    },
    mounted() {
        const vm = this; // Almacena la referencia a la instancia de Vue
        this.authUserStore = useAuthUserStore();

        const myDropzone = new Dropzone("#my-dropzone", {
            url: "/web/files",
            autoProcessQueue: false,
            addRemoveLinks: true, // Agregar enlaces para eliminar archivos
            uploadMultiple: true, // Agregar varios a la vez
            dictDefaultMessage: "Arrastra y suelta archivos aquí o haz clic para seleccionar archivos.",
            dictRemoveFile: "Eliminar archivo", // Texto para eliminar archivo
            dictCancelUpload: "Cancelar subida", //Texro para cancelar subida
            uploadprogress: false, // Deshabilitar la barra de progreso
            resizeWidth: false, // Desactivar redimensionamiento
            resizeHeight: false,
            resizeQuality: false,
        });

        document.getElementById("submit-files-button").addEventListener("click", function() {
            vm.saveFiles(myDropzone.files).then(result => {
                if (result) {
                    myDropzone.removeAllFiles(); // Vaciar los archivos del Dropzone
                }
            });
        });  
    },
    methods:{
        saveFiles(files) {
            return new Promise((resolve) => {
                const formData = new FormData();
                formData.append('email', this.authUserStore.user.email);

                // Iterar a través de cada archivo y agregarlos al FormData
                files.forEach((file, index) => {
                    formData.append('files[]', file); // Agregar cada archivo al array 'files[]'
                });

                // Funcionalidad para enviar la solicitud POST
                axios.post('/web/files', formData, axiosConfig)
                    .then(response => {
                        showSuccessMessage('¡Archivos cargados exitosamente!');
                        this.$emit('upload-files');
                        resolve(true); // Resuelve con éxito
                    })
                    .catch(error => {
                        const errors = error.response.data.errors;
                        showErrorGroupMessages(errors);
                    });
            });
        },
    }
};
</script>

<style>
.styled-dropzone {
    border: 2px dashed #ccc !important;
    min-height: 150px;
    padding: 20px;
    border-radius: 5px;
    background-color: #f9f9f9;
    text-align: center;
    cursor: pointer;
    /* Otros estilos que desees agregar */
}

.styled-dropzone p {
    font-size: 16px;
    color: #555;
    /* Otros estilos para el texto informativo */
}

.dropzone .dz-progress {
    display: none !important;
}
</style>