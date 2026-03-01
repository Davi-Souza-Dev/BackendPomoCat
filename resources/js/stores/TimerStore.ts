import api from '@/api'
import type { Card, Timer } from '@/types'
import { defineStore } from 'pinia'
import { useUserStore } from './UserStore'

interface TimerState {
  timer: Timer
  interval: number
  progress: number
  newCard: {
    card: Card
    completed: boolean
  }
  minReal: number
}
export const useTimerStore = defineStore('TimerStore', {
  state: (): TimerState => {
    return {
      timer: {
        min: 50,
        sec: 0,
        start: false,
      },
      minReal: 0,
      interval: 0,
      progress: 0,
      newCard: {
        card: {
          id: 0,
          title: '',
          image_url: '/images/bloqueado.png',
          rarity: 'comum',
        },
        completed: false,
      },
    }
  },
  actions: {
    async startTimer() {
      if (this.timer.start == false) {
        this.minReal = this.timer.min
        this.timer.start = !this.timer.start
        const totalSecs = Number(this.timer.min) * 60 + Number(this.timer.sec)
        this.interval = setInterval(() => {
          document.title = `Timer: ${this.timer.min.toString().padStart(2, '0')}:${this.timer.sec.toString().padStart(2, '0')}`
          if (this.timer.sec > 0) {
            this.timer.sec--
          } else if (this.timer.min > 0 && this.timer.sec == 0) {
            this.timer.min--
            this.timer.sec = 59
          } else if (this.timer.min == 0 && this.timer.sec == 0) {
            document.title = 'Timer Finalizado!'
            this.playAudio()
            clearInterval(this.interval)
            this.timer.start = !this.timer.start
            this.timer.min = 50
            this.timer.sec = 0

            this.saveFocus('completed')
          }

          let secReaming = this.timer.min * 60 + this.timer.sec
          let tempoDecorrido = totalSecs - secReaming

          this.progress = Math.floor((tempoDecorrido / totalSecs) * 100)
        }, 1000)
      } else {
        this.saveFocus('interruped')
      }
    },
    playAudio() {
      const audio = new Audio('/timer.mp3')
      audio.currentTime = 0
      audio.volume = 1
      audio.play()

      setTimeout(() => {
        audio.pause()
        audio.currentTime = 0
      }, 10000)
    },
    async saveFocus(status: string) {
      const user = useUserStore();
      const formData = new FormData()
      formData.append('duration', this.minReal.toFixed())
      formData.append('status', status)
      const response = await api.post(`/newfocus/${user.user.username}`, formData)
      clearInterval(this.interval)
      this.timer.start = false
      this.timer.min = this.minReal
      this.timer.sec = 0

      if (status == 'completed') {
        this.newCard.card = response.data.success.card
        this.newCard.completed = true
      }
    },
  },
})
