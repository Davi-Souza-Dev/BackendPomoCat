import api from '@/api';
import type { FormRegisterError, User } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { toast } from 'vue-sonner';

interface UserState {
    user: User;
    todayfocus: number;
}
export const useUserStore = defineStore('UserStore', {
    state: (): UserState => {
        return {
            user: {
                username: '',
                email: '',
                photo: '',
            },
            todayfocus: 0,
        };
    },
    getters: {
        username: () => usePage().props.auth.username,
    },
    actions: {
        async loginForm(email: string, password: string) {
            try {
                if (email == '' && password == '') {
                    toast.error('Email e Senha obrigátorios!');
                    return;
                }
                const formData = useForm({
                    email: email,
                    password: password,
                });

                formData.post('/auth/login', {
                    onError: (error: any) => {
                        toast.error(error.message)
                    },
                });
            } catch ($error) {}
        },

        async register(
            username: string,
            email: string,
            password: string,
            password_confirmation: string,
        ) {
            try {
                const formData = useForm({
                    username: username,
                    email: email,
                    password: password,
                    password_confirmation: password_confirmation,
                });

                formData.post('/auth/register', {
                    onSuccess: (message: any) => {
                        toast.success('Cadastro realizado!');
                    },
                    onError: (error: FormRegisterError) => {
                        const errorMessage = Object.values(error)[0];
                        toast.error(errorMessage);
                    },
                });
            } catch (error: any) {
                toast.error(error.errors);
            }
        },
        async loginGoogle(
            email: string | null,
            uid: string | undefined,
            username: string | null,
            photo: string | null,
        ) {
            if (
                email != null &&
                uid != undefined &&
                username != null &&
                photo != null
            ) {
                const formData = useForm({
                    email: email,
                    password: uid,
                    username: username,
                    photo: photo,
                });

                formData.post('/auth/logingoogle', {
                    onError: ($error) => {
                        toast.error($error.error)
                    },
                });
            }
        },
        async logout() {
            this.user.username = '';
            this.user.email = '';
        },
    },
});
