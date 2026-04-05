import api from '@/api';
import type { Audio, Card } from '@/types';
import { defineStore } from 'pinia';
import { toast } from 'vue-sonner';

interface PlaylistState {
    active: boolean;
    playlist: Audio[];
    actualAudio: Audio;
}

export const usePlaylistStore = defineStore('playlistStore', {
    state: (): PlaylistState => {
        return {
            active: false,
            actualAudio: {
                id: 0,
                file: null,
                path: null,
                title: '',
                order: 0,
                url: null,
            },
            playlist: [],
        };
    },
    actions: {
        toggleDialog() {
            this.active = !this.active;
        },
        async getPlaylist() {
            const response = await api.get('/audio/getplaylist');
            this.playlist = response.data;
        },
        async deleteAudio(audio: Audio) {
            const response = await api.post('/audio/delete', {
                id: audio.id,
            });

            if (response.status == 200) {
                const index = this.playlist.findIndex(
                    (audioPlaylist: Audio) => {
                        return audioPlaylist.id === audio.id;
                    },
                );

                if (index !== -1) {
                    this.playlist.splice(index, 1);
                }

                this.playlist = this.playlist.filter((audioPlaylist: Audio) => {
                    return audioPlaylist.id !== audio.id;
                });
                
                toast.success('Áudio deletado!');
            }else{
              toast.error('Algo deu errado!')
            }
        },
    },
});
