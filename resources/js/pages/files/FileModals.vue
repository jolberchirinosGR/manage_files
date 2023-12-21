<template>
  <!-- Modal de edicion archivo -->
  <div class="modal fade" id="fileModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white'">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        <i class="fa fa-edit"></i> Editar archivo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <img :src="file.img" style="width: 100%; border: 2px dashed #928e8e; border-radius: 5px; padding: 5px;">
                        </div>
                    </div>

                    <br>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <label for="name">Cambiar Nombre</label>
                            <input v-model="file.name" type="text" class="form-control" id="name" placeholder="Nombre" name="" autocomplete="off">
                        </div>
                    </div>

                    <br>

                    <div class="row">
                        <div class="col-md-3">
                          <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-tags"></i></span>
                            </div>
                            <input v-model="file.type" class="form-control" disabled>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-weight-hanging"></i></span>
                            </div>
                            <input v-model="file.size" class="form-control" disabled>
                          </div>
                        </div>
                        <div class="col-md-4">
                          <div class="input-group mb-3">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <input v-model="file.created_at" class="form-control" disabled>
                          </div>
                        </div>
                    </div>

                    <span><strong> Ultima actualización: </strong></span>
                    <div class="row">
                        <div class="col-md-12">
                          <span>Actualizado por {{ file.updated_by }} a las {{ file.updated_at }}</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button @click="updateFile()" type="submit" class="btn btn-success">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { showSuccessMessage, showErrorMessage, showErrorGroupMessages, useSweetAlert }  from '../../sweet.js';
import { useAuthUserStore } from '../../stores/AuthUserStore';

export default {
  emits: ['update-files'], //Eventos que se generan en este componente
  data() {
    return {
      //Objeto para la edicion
      file: {
        id: '',
        name: '',
        img: '',
        size: '',
        type: '',
        created_at: '',
        updated_at: '',
        updated_by: '',
      },
      authUserStore: null,
    };
  },
  methods: {
    // Limpiar formulario
        clearForm() {
            this.file = {
                id: '',
                name: '',
                img: '',
                size: '',
                type: '',
                created_at: '',
                updated_at: '',
                updated_by: '',
            };
            this.authUserStore = null
        },

    //Abrir model de editar o crear usuario
        openFormModal(file) {
            this.file.id = file.id ?? null;
            this.file.img = file.img ?? null;
            this.file.name = file.name ?? null;
            this.file.size = file.size ?? null;
            this.file.type = file.type ?? null;
            this.file.created_at = file.created_at ?? null;
            this.file.updated_at = file.updated_at ?? null;
            this.file.updated_by = file.updated_by ?? null;

            $('#fileModal').modal('show');
        },
        
    //Cerrar modal de editar o crear usuario
        closeFormModal() {
            this.clearForm();
            $('#fileModal').modal('hide');
        },

    //Funcion para actualizar
        updateFile() {
            const data = {
                id: this.file.id,
                name: this.file.name,
                type: this.file.type,
                updated_by: this.authUserStore.user.id,
            };

            axios.put(`/web/files/${data.id}`, data).then(response => {
                this.closeFormModal();               
                showSuccessMessage('¡Archivo actualizado exitosamente!');
                this.$emit('update-files');
            })
            .catch(error => {
                const errors = error.response.data.errors;
                showErrorGroupMessages(errors)
            });
        },
  },
  created() {
    this.clearForm();
    this.authUserStore = useAuthUserStore();
  },
};
</script>
