//Componentes Base
import NotFound from './components/NotFound.vue';
import Login from './pages/auth/Login.vue';

//Componentes nuevos
import UserList from './pages/users/UserList.vue';
import FileList from './pages/files/FileList.vue';

export default [
    {
        path: '/not_found',
        name: 'Not Found',
        component: NotFound,
    },

    {
        path: '/login',
        name: 'Login',
        component: Login,
    },

    {
        path: '/',
        name: 'Inicio',
        component: FileList,
    },

    {
        path: '/users',
        name: 'Usuarios Menu',
        component: UserList,
    },
]
