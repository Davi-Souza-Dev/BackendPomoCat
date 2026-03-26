import api from '@/api';
import type { User } from '@/types';
import { useForm, usePage } from '@inertiajs/vue3';
import { defineStore } from 'pinia';

interface UserState {
    user: User;
    todayfocus: number,
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
    getters:{
        authUser: () => usePage().props.auth.user
    },
    actions: {
        async loginForm(email: string, password: string) {
            try {
                const formData = useForm({
                    email: email,
                    password: password,
                });

                formData.post('/auth/login', {
                    onError: ($error) => {
                        return {
                            title: $error,
                        };
                    },
                });
            } catch ($error) {
                alert($error);
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
                        return {
                            title: $error,
                        };
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
