<script setup lang="ts">
import { PlayIcon, Settings, XIcon } from 'lucide-vue-next'
import Button from '../ui/button/Button.vue'
import { ButtonGroup } from '@/components/ui/button-group'
import { useTimerStore } from '@/stores/TimerStore'
import DialogConfig from './DialogConfig.vue'
import { ref } from 'vue'
import CentralIcon from './CentralIcon.vue'
import UnlockCard from './UnlockCard.vue'
import PlayerAudio from './PlayerAudio.vue'
const timerStore = useTimerStore()
const showDialogConfig = ref(false)
</script>
<template>
  <DialogConfig :open="showDialogConfig" @update:open="showDialogConfig = false" />
  <UnlockCard/>
  
  <div
    class="flex flex-col justify-center gap-5 md:gap-10 items-center"
    v-if="timerStore.timer.start == false"
  >
    <CentralIcon />

    <h1 class="font-extrabold uppercase text-6xl md:text-7xl tracking-tighter">
      {{ String(timerStore.timer.min).padStart(2, '0') }}:{{
        String(timerStore.timer.sec).padStart(2, '0')
      }}
    </h1>

    <ButtonGroup class="md:scale-125 transition-transform">
      <Button 
        class="w-32 h-12 md:w-40 md:h-10 border-2 border-primary-foreground" 
        @click="timerStore.startTimer()"
      >
        <PlayIcon class="fill-foreground md:scale-125" />
      </Button>
      <Button 
        class="w-12 h-12 md:h-10 md:w-10 border-2 border-foreground px-5" 
        @click="showDialogConfig = true"
      >
        <Settings class="md:scale-125" />
      </Button>
    </ButtonGroup>

    <!-- <PlayerAudio/> -->
  </div>

  <div v-else class="flex flex-col justify-center gap-5 md:gap-10 items-center">
    <CentralIcon />

    <h1 class="font-extrabold uppercase text-6xl md:text-7xl tracking-tighter">
      {{ String(timerStore.timer.min).padStart(2, '0') }}:{{
        String(timerStore.timer.sec).padStart(2, '0')
      }}
    </h1>

    <ButtonGroup class="md:scale-125 transition-transform">
      <Button 
        class="w-32 h-12 md:w-50 md:h-10 border-2 border-primary-foreground" 
        @click="timerStore.startTimer()"
      >
        <XIcon class="fill-foreground md:scale-125" />
      </Button>
    </ButtonGroup>

  </div>
</template>