import api from '@/api';
import { Audio } from '@/types';
import { progress, useForm } from '@inertiajs/vue3';
import { defineStore } from 'pinia';
import { usePlaylistStore } from './PlaylistStore';

interface DialogState {
    active: boolean;
    audio: Audio;
    progrees: number;
    alert: boolean;
}

export const useDialogAudio = defineStore('dialogAudio', {
    state: (): DialogState => {
        return {
            active: false,
            audio: {
                id: 0,
                file: null,
                path: null,
                title: '',
                order: 0,
                url: null
            },
            progrees: 0,
            alert: false,
        };
    },
    actions: {
        toggleDialog() {
            this.active = !this.active;
        },
        clearDialog(){
            this.audio.file = null;
            this.audio.title = '';
        },
        async setAudio() {
            const formData = useForm({
                ...this.audio,
            });

            this.active = false;
            this.alert = true;

            const response = await api.post('/audio/setaudio', formData, {
                onUploadProgress: (progressEvent) => {
                    if (progressEvent.total) {
                        const percentCompleted = Math.round(
                            (progressEvent.loaded * 100) / progressEvent.total,
                        );
                        this.progrees = percentCompleted;
                    }
                },
            });

            if (response.status == 200) {
                const playlistStore = usePlaylistStore();
                playlistStore.playlist.push(response.data.audio)
                setTimeout(()=>{
                    this.alert = false;
                    this.active = false;
                    this.clearDialog();
                },500);
            }
        },
    },
});
