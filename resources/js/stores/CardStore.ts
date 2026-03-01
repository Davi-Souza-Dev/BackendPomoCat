import api from '@/api'
import type { Card } from '@/types'
import { defineStore } from 'pinia'
import { useUserStore } from './UserStore'

interface CardState {
  cards: Card[]
}

export const useCardStore = defineStore('CardStore', {
  state: (): CardState => {
    return {
      cards: [],
    }
  },
  actions: {
    async getCards() {
      try{
        const userStore = useUserStore();
        const response = await api.get(`/getcatalog/${userStore.user.username}`);
        console.log(response);
        return response.data
      }catch($erro){
        console.log($erro)
      }
    },
  },
})
