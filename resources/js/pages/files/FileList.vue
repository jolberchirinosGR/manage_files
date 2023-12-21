<template>
    <!-- Header de la pagina -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listado de archivos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/">Inicio</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            Archivos
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido de la pagina -->
    <div class="content">
        <div class="container-fluid">
            <!-- Botones bonitos -->
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <button type="button" class="mb-2 btn btn-info ml-2" data-toggle="collapse" data-target="#filtersAccordion" aria-expanded="true">
                        <i class="fas fa-sliders-h"></i>
                        Mostrar Filtros
                    </button>
                </div>
            </div>

            <!-- Filtros en un acordeón colapsable -->
            <div class="collapse" id="filtersAccordion">
                <div class="card card-body">
                <div class="row">
                    <div class="col-md-1">
                        <!-- Selección de paginación -->
                        <div class="form-group">
                            <label for="paginationNumber">Paginación:</label>
                            <select v-model="paginationNumber" class="form-control">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!-- Input de búsqueda por nombre -->
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" v-model="nameSearch" class="form-control" placeholder="Buscar por nombre..." />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!-- Input de búsqueda por peso -->
                        <div class="form-group">
                            <label for="size">Peso:</label>
                            <input v-model="sizeSearch" type="text" class="form-control" placeholder="Buscar por peso..." >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!-- Input de búsqueda por tipo -->
                        <div class="form-group">
                            <label for="type">Tipo:</label>
                            <input v-model="typeSearch" type="text" class="form-control" placeholder="Buscar por tipo..." >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <!-- Input de búsqueda por creado -->
                        <div class="form-group">
                            <label for="updateBy">Creado por:</label>
                            <input v-model="updateBySearch" type="text" class="form-control" placeholder="Buscar por creado por..." >
                        </div>
                    </div>
                    <div class="col-md-2">
                    <!-- Input de búsqueda por fecha de creacion -->
                    <div class="form-group">
                        <label for="start-time">Fecha creado:</label>
                        <div class="input-group">
                            <input v-model="dateSearch" type="text" class="form-control flatpickr" id="start-time">
                            <div class="input-group-append">
                                <a href="#" @click="dateSearchNull" class="btn btn-outline-secondary">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="card">
                <FileDropzone
                    @upload-files="uploadFiles"
                />

                <div class="card-body">
                    <div class="table-responsive">
                        <!-- Listado de las archivos y con el checkbox para eliminar varias o todas -->
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th @click="sortBy('name')">
                                        Nombre
                                        <span v-if="orderByColumn === 'name'">
                                            <i v-if="orderByType === 'asc'" class="fas fa-arrow-up"></i>
                                            <i v-else-if="orderByType === 'desc'" class="fas fa-arrow-down"></i>
                                        </span>
                                    </th>
                                    <th @click="sortBy('size')">
                                        Peso
                                        <span v-if="orderByColumn === 'size'">
                                            <i v-if="orderByType === 'asc'" class="fas fa-arrow-up"></i>
                                            <i v-else-if="orderByType === 'desc'" class="fas fa-arrow-down"></i>
                                        </span>
                                    </th>
                                    <th @click="sortBy('type')">
                                        Tipo
                                        <span v-if="orderByColumn === 'type'">
                                            <i v-if="orderByType === 'asc'" class="fas fa-arrow-up"></i>
                                            <i v-else-if="orderByType === 'desc'" class="fas fa-arrow-down"></i>
                                        </span>
                                    </th>
                                    <th @click="sortBy('updated_by')">
                                        Modificado por
                                        <span v-if="orderByColumn === 'updated_by'">
                                            <i v-if="orderByType === 'asc'" class="fas fa-arrow-up"></i>
                                            <i v-else-if="orderByType === 'desc'" class="fas fa-arrow-down"></i>
                                        </span>
                                    </th>
                                    <th @click="sortBy('created_at')">
                                        Creado
                                        <span v-if="orderByColumn === 'created_at'">
                                            <i v-if="orderByType === 'asc'" class="fas fa-arrow-up"></i>
                                            <i v-else-if="orderByType === 'desc'" class="fas fa-arrow-down"></i>
                                        </span>
                                    </th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <!-- Esta tabla esta paginada desde el controlador -->
                            <tbody v-if="totalFiles > 0">
                                <!-- Aqui en este componente se imprimen los resultasdos de la consulta a BD -->
                                <FileListItem v-for="(file, index) in files.data"
                                    :key="file.id"
                                    :file=file
                                    @open-update-modal="openUpdateModal"
                                    @delete-file="deleteFiles"
                                />
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="24" class="text-center">Sin resultados...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Esta paginacion viene del controlador y formateada por boostrap -->
            <Bootstrap4Pagination :limit="15" :data="files" @pagination-change-page="getFiles" />
        </div>
    </div>

    <file-modals ref="fileModal"
        @update-files="updateFiles"
    />
</template>

<script>
import axios from 'axios';
import { debounce } from 'lodash';
import { Bootstrap4Pagination } from 'laravel-vue-pagination';
import flatpickr from "flatpickr";
import FileDropzone from './FileDropzone.vue'; // Importa el nuevo componente
import FileListItem from './FileListItem.vue';
import FileModals from './FileModals.vue';

export default {
  components: {
    FileListItem,
    FileModals,
    Bootstrap4Pagination,
    FileDropzone, // Registra el componente
  },
  data() {
    return {
      //Objeto para la edicion
      file: {
        id: '',
        name: '',
        email: '',
        password: '',
        confirm_password: '',
        id_role: '',
      },
      //Modales y titulos
      id: 0,
      update: false,
      //Variables para comprobar los resultados y el total de los datos
      roles: [],
      files: [],
      totalFiles: 0,
      //Filtros para el listado
      nameSearch: null,
      sizeSearch: null,
      typeSearch: null,
      updateBySearch: null,
      dateSearch: null,
      //Generales
      paginationNumber: 5,
      orderByColumn: '',
      orderByType: 'none', // none, asc, desc
    };
  },
  methods: {
    //Obtener todas los Archivos
      getFiles(page = 1) {
        axios
            .get(`/web/files?page=${page}`, {
                params: {
                    name: this.nameSearch,
                    size: this.sizeSearch,
                    type: this.typeSearch,
                    updateBySearch: this.updateBySearch,
                    dateSearch: this.dateSearch,
                    //Generales
                    pagination: this.paginationNumber,
                    order: this.orderByType,
                    column: this.orderByColumn,
                },
            })
            .then((response) => {
                this.files = response.data;
                this.totalFiles = this.files.data.length;
            });
      },

    //Obetener roles
      getRoles(){
          axios.get('/web/roles').then((response) => {
              this.roles = response.data;
          })
      },    

    //Obetener ordenación
      sortBy(column) {
        if (this.orderByColumn === column) {
            if (this.orderByType === 'none') {
                this.orderByType = 'asc';
            } else if (this.orderByType === 'asc') {
                this.orderByType = 'desc';
            } else {
                this.orderByType = 'none';
                this.orderByColumn = '';
            }
        } else {
            this.orderByColumn = column;
            this.orderByType = 'asc';
        }
    },
    
    //Funcion para actualizar
    updateFiles() {
        this.getFiles();
    },

    // Método para abrir el modal de creación
    openUpdateModal(data) {
        this.$refs.fileModal.openFormModal(data);
    },

    //Funcion para actualizar
    deleteFiles() {
        this.getFiles();
    },

    //vaciar filtro de fecha
    dateSearchNull(){
        this.dateSearch = null;
    },

    //Subir Archivos
    uploadFiles(){
        this.getFiles();
    }
  },
  watch: {
    nameSearch: debounce(function () {
      this.getFiles();
    }, 300),
    sizeSearch: debounce(function () {
      this.getFiles();
    }, 300),
    typeSearch: debounce(function () {
      this.getFiles();
    }, 300),
    updateBySearch: debounce(function () {
      this.getFiles();
    }, 300),
    dateSearch: debounce(function () {
      this.getFiles();
    }, 300),
    paginationNumber: debounce(function () {
      this.getFiles();
    }, 300),
    orderByType: debounce(function () {
      this.getFiles();
    }, 300),
  },
  created() {
    this.getFiles();
    this.getRoles();
  },
  mounted() {
    flatpickr(".flatpickr", {
        enableTime: false,
        dateFormat: "Y-m-d",
        locale: {
        firstDayOfWeek: 1,
        weekdays: {
          shorthand: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          longhand: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        },
        months: {
          shorthand: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Оct', 'Nov', 'Dic'],
          longhand: ['Enero', 'Febreo', 'Мarzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        },
      },
    });
  }
};
</script>
