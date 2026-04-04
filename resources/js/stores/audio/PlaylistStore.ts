import api from '@/api'
import type { Audio, Card } from '@/types'
import { defineStore } from 'pinia'

interface PlaylistState {
  active: boolean
  playlist: Audio[]
}

export const usePlaylistStore = defineStore('playlistStore', {
  state: (): PlaylistState => {
    return {
      active: false,
      playlist: []
    }
  },
  actions: {
    toggleDialog() {
      this.active = !this.active;
    },
    async getPlaylist(){
        const response = await api.get('/audio/getplaylist');
        this.playlist = response.data
        console.log(this.playlist)
    }
  },
})
