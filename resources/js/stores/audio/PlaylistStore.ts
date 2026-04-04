import api from '@/api'
import type { Card } from '@/types'
import { defineStore } from 'pinia'

interface PlaylistState {
  active: boolean
}

export const usePlaylistStore = defineStore('playlistStore', {
  state: (): PlaylistState => {
    return {
      active: false,
    }
  },
  actions: {
    toggleDialog() {
      this.active = !this.active;
    },
  },
})
