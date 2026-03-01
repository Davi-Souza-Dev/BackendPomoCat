import api from '@/api'
import type { Card, Timer } from '@/types'
import { defineStore } from 'pinia'
import { useCardStore } from './CardStore'

interface CatalogState {
  cards: Card[]
  active: boolean
  totalCards: number
}
export const useCatalogStore = defineStore('CatalogStore', {
  state: (): CatalogState => {
    return {
      cards: [],
      active: false,
      totalCards: 0,
    }
  },
  actions: {
    async showCatalog() {
      try {
        const cardStore = useCardStore()
        const response = await cardStore.getCards()
        this.cards = response.cards
        this.totalCards = response.total
        this.active = !this.active
      } catch ($erro) {
        console.log($erro);
      }
    },
  },
})
