<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from '@/components/ui/alert-dialog'
import { useTimerStore } from '@/stores/TimerStore'
import { ref } from 'vue'
import Button from '../ui/button/Button.vue'

const timerStore = useTimerStore()
const click = ref(false)

const showCard = () => {
  click.value = true

  const timeout = setTimeout(() => {
    timerStore.newCard.completed = false
  }, 1200)
}
</script>

<template>
  <AlertDialog :open="timerStore.newCard.completed">
    <AlertDialogContent
      class="bg-transparent border-0 outline-0 shadow-none flex align-middle justify-center items-center text-center"
    >
      <AlertDialogHeader>
        <AlertDialogTitle class="text-center font-extrabold text-3xl">Nova Carta</AlertDialogTitle>
        <AlertDialogDescription class="text-center font-extrabold">
          {{ click == true ? 'Clique novamente para sair' : 'Clique/Toque para desbloquear' }}
        </AlertDialogDescription>
        <div
          class="w-full flex align-middle justify-center"
          @click="showCard()"
          :class="click == true ? 'flip-vertical-left' : ''"
        >
          <div
            class="w-40 h-50 bg-background-app rounded flex flex-col gap-1 justify-around align-middle items-center"
          >
            <div v-if="click == false">
              <img src="/images/bloqueado.png" class="w-30" />
            </div>
            <div v-if="click == true">
              <div
                class="w-35 h-50 bg-background-app rounded flex flex-col gap-1 justify-around align-middle items-center flip-fix"
              >
                <img :src="timerStore.newCard.card.image_url" class="w-25" :class="`rarity-${timerStore.newCard.card.rarity}`"/>
                <span class="text-l font-pixel uppercase text-center">
                  {{ timerStore.newCard.card.title }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </AlertDialogHeader>
    </AlertDialogContent>
  </AlertDialog>
</template>

<style scoped>
.flip-vertical-left {
  -webkit-animation: flip-vertical-left 0.4s cubic-bezier(0.455, 0.03, 0.515, 0.955) both;
  animation: flip-vertical-left 0.4s cubic-bezier(0.455, 0.03, 0.515, 0.955) both;
}
@-webkit-keyframes flip-vertical-left {
  0% {
    -webkit-transform: rotateY(0);
    transform: rotateY(0);
  }
  100% {
    -webkit-transform: rotateY(-180deg);
    transform: rotateY(-180deg);
  }
}
@keyframes flip-vertical-left {
  0% {
    -webkit-transform: rotateY(0);
    transform: rotateY(0);
  }
  100% {
    -webkit-transform: rotateY(-180deg);
    transform: rotateY(-180deg);
  }
}

.flip-fix {
  transform: rotateY(180deg);
}


.rarity-image {
  transition: all 0.3s ease-in-out;
  padding: 10px
}

/* Comum */
.rarity-comum {
  filter: drop-shadow(0 0 8px #9ca3af);
}

/* Raro */
.rarity-raro {
  filter: drop-shadow(0 0 12px #3b82f6);
}

/* Épico */
.rarity-epíco {
  filter: drop-shadow(0 0 15px #a855f7);
  animation: pulse-rarity 2s infinite;
}

/* Lendário */
.rarity-lendário {
  filter: drop-shadow(0 0 20px #f59e0b) drop-shadow(0 0 35px #fbbf24);
  animation: shine-gold 3s infinite;
}


@keyframes pulse-rarity {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; filter: drop-shadow(0 0 25px rgba(168, 85, 247, 0.9)); }
}

@keyframes shine-gold {
  0% { filter: drop-shadow(0 0 15px #f59e0b); }
  50% { filter: drop-shadow(0 0 30px #fbbf24); transform: scale(1.02); }
  100% { filter: drop-shadow(0 0 15px #f59e0b); }
}

</style>
