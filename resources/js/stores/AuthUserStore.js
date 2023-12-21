import axios from 'axios';
import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAuthUserStore = defineStore('AuthUserStore', () => {
    const user = ref({
        id: '',
        name: '',
        email: '',
        role: '',
    });

    const getAuthUser = async () => {
        await axios.get('/web/profile')
            .then((response) => {
                user.value = response.data;
            });
    };

    return { user, getAuthUser };
});
