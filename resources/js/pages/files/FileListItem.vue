<template>
    <tr>
      <td style="text-align: center;">
        
        <img v-if="file.type === 'jpg' || file.type === 'png' ||  file.type === 'gif' ||  file.type === 'jpeg'" 
                  :src=getFile(file) class="img-fluid rounded-start" style="width: 20%;">

        <img v-else :src=getFile(file) class="img-fluid rounded-start" style="width: 40px; height: 40px;">

        <br>
        <strong>{{ getNameWithoutExtension(file) }}</strong>
      </td>
      <td>{{ file.size + 'mb' }}</td>
      <td>{{ file.type }}</td>
      <td>{{ getUserName(file.updated_by) }}</td>
      <td>{{ getDate(file.created_at) }}</td>
      <td>
        <div class="d-flex justify-content-center">
          <button @click="downloadFile(file)" type="button" class="btn btn-primary mx-2">
            <i class="fa fa-download mr-1"></i>
            Descargar
          </button>
          <button v-if="acceptRole" @click="openUpdateModal(file)" type="button" class="btn btn-success mx-2">
            <i class="fa fa-edit mr-1"></i>
            Renombrar
          </button>
          <button v-if="acceptRole" @click="openDeleteModal(file)" type="button" class="btn btn-danger mx-2">
            <i class="fa fa-trash mr-1"></i>
            Eliminar
          </button>

        </div>
      </td>
    </tr>
</template>
  
<script>
import axios from 'axios';
import { showSuccessMessage, showErrorMessage, showErrorGroupMessages, useSweetAlert }  from '../../sweet.js';
import { useAuthUserStore } from '../../stores/AuthUserStore';

  export default {
    emits: ['open-update-modal', 'delete-file'], //Eventos que se generan en este componente
    props: {
      file: Object,
    },
    data() {
      return {
        data: this.file,
        id: 0,
        usersAll: [], //Varaibel para mostrar el nombre del usuario
        acceptRole: null //Para cargar el rol
      };
    },
    created() {
      this.getUsers();
      const user = useAuthUserStore(); //cargar sesion del usuario logeado
      this.acceptRole = user.user.role.name === 'Admin' ? true : false;
    },
    watch: {
      file(newFile) {
        this.data = newFile;
      },
    },
    methods: {
      //Obtener todas los Roles
        getUsers(){
          axios.get('/web/users_unpaged').then((response) => {
              this.usersAll = response.data;
          })
        },

      //Obtener el nombre del rol 
        getUserName(userId) {
          const user = this.usersAll.find(user => user.id === userId);
          return user ? user.name : 'Usuario no encontrado';
        },

      // Método para abrir el modal de edición
        openUpdateModal(file) {
          const fileFormated = {
            id: file.id,
            img: this.getFile(file),
            name: this.getNameWithoutExtension(file),
            type: file.type,
            size: file.size + 'mb',
            updated_by: this.getUserName(file.updated_by),
            created_at: this.getDate(file.created_at),
            updated_at: this.getDateWithHour(file.updated_at),
          };
          this.$emit('open-update-modal', fileFormated);
        },

      // Método para abrir el modal de eliminación
        openDeleteModal(file) {
            this.id = file.id;
            this.showDeleteConfirmation();
        },

      // Eliminar extensión del nombre
        getNameWithoutExtension(file) {
          const lastDotIndex = file.name.lastIndexOf('.'); // Encuentra el último punto en el nombre del archivo
          if (lastDotIndex === -1) {
            return file.name; // Si no hay punto, devuelve el nombre completo
          } else {
            return file.name.substring(0, lastDotIndex); // Devuelve el nombre sin la extensión
          }
        },

      //Obtener fecha en formato carbon
        getDate(date) {
          const d = new Date(date);
          const day = String(d.getDate()).padStart(2, '0'); // Ajusta el día a dos dígitos
          const month = String(d.getMonth() + 1).padStart(2, '0'); // Ajusta el mes a dos dígitos
          const year = d.getFullYear();
          return `${day}-${month}-${year}`;
        },

      //Obtener fecha y hora en formato carbon
        getDateWithHour(date) {
          const d = new Date(date);
          const day = String(d.getDate()).padStart(2, '0'); // Ajusta el día a dos dígitos
          const month = String(d.getMonth() + 1).padStart(2, '0'); // Ajusta el mes a dos dígitos
          const year = d.getFullYear();
          const hour = d.getHours();
          const minutes = d.getMinutes();

          return `${hour}:${minutes} del ${day}-${month}-${year}`;
        },

      //Devolver la imagen y sino una iamgen precargada para eso.
        getFile(file) {
          const fileType = file.type.toLowerCase();
          const fileName = file.name.toLowerCase();

          if (fileType === 'jpg' || fileType === 'jpeg' || fileType === 'png' || fileType === 'gif') {
            return `storage/files/${fileName}`;
          }

          const commonFormats = {
            //Compresión
            'rar': 'type/comp.png',
            'zip': 'type/comp.png',
            'tar': 'type/comp.png',
            '7z': 'type/comp.png',
            'tar.gz': 'type/zip.png',
            'gz': 'type/zip.png',
            'iso': 'type/iso.png',

            //Documentos
            'doc': 'type/doc.png',
            'docx': 'type/doc.png',
            'txt': 'type/doc.png',
            'xlsx': 'type/excel.png',
            'xls': 'type/excel.png',
            'ppt': 'type/pp.png',
            'pptx': 'type/pp.png',
            'pdf': 'type/pdf.png',

            //Ejectuables
            'exe': 'type/exe.png',
            'apk': 'type/exe.png',

            //Medias
            'mkv': 'type/mkv.png',
            'mp4': 'type/mkv.png',
            'mp3': 'type/mp3.png',
            'ipa': 'type/mp3.png',
            
            //Programmer
            'sql': 'type/sql.png',
            'html': 'type/html.png',
            'json': 'type/json.png',
            'xml': 'type/html.png',
            'php': 'type/html.png',

            'psd': 'type/image.png',
            'bmp': 'type/image.png',
            'tiff': 'type/image.png',
            'psd': 'type/image.png',
            'ai': 'type/image.png',
            'webb': 'type/image.png',
            // Agrega aquí más formatos comunes si es necesario
          };

          const defaultImage = 'type/files.png';

          if (commonFormats[fileType]) {
            return commonFormats[fileType];
          } else {
            return defaultImage;
          }
        },

      //Descargar el archivo en cuestión
        downloadFile(file) {
          const filePath = `storage/files/${file.name}`;

          // Crear un enlace temporal para descargar el archivo
          axios({
            url: filePath,
            method: 'GET',
            responseType: 'blob', // Indicar que se espera un objeto blob como respuesta
          })
            .then((response) => {
              const url = window.URL.createObjectURL(new Blob([response.data]));
              const link = document.createElement('a');
              link.href = url;
              link.setAttribute('download', file.name); // Establecer el nombre del archivo
              document.body.appendChild(link);
              link.click();

              // Ocultar el modal después de descargar
              showSuccessMessage('Tu archivo comenzará a descargarse en breve...');
            })
            .catch((error) => {
              // Manejar cualquier error que ocurra durante la descarga
              console.error('Error al descargar el archivo:', error);
              showErrorMessage('Error al descargar el archivo');
            });
        },

      //Modal de eliminar
        showDeleteConfirmation() {
          const swal = useSweetAlert();
          
          swal.fire({
              title: '¡Advertencia!',
              text: '¿Estás seguro de eliminar este usuario?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              confirmButtonText: 'Eliminar',
              cancelButtonText: 'Cancelar'
          }).then((result) => {
              if (result.isConfirmed) {
                // Si el usuario confirma la eliminación, llamas a la función deleteUser()
                this.deleteFile();
              }
          });
        },

      //Funcion para eliminar
        deleteFile() {
            axios.delete(`/web/files/${this.id}`)
            .then(() => {
                showSuccessMessage('Archivo eliminado exitosamente!');
                this.$emit('delete-file');
            }).catch(error => {
                console.log(error);
                const errors = error.response.data.errors;

                showErrorGroupMessages(errors)
            });
        },
    },
  };
</script>