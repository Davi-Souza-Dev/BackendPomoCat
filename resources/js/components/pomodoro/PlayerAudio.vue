<script setup lang="ts">
import { ref } from 'vue'
import { Play, Pause, SkipBack, SkipForward, RotateCcw } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
const isPlaying = ref(false)
const progress = ref(10)

const togglePlay = () => {
  isPlaying.value = !isPlaying.value
}
</script>

<template>
  <div class="bg-background-dialog text-white p-4 rounded-sm w-full max-w-100 flex items-center gap-5 shadow-2xl border border-fore">
    
    <div class="relative shrink-0">
      <img 
        src="https://upload.wikimedia.org/wikipedia/commons/6/6a/Johann_Sebastian_Bach.jpg" 
        alt="Bach"
        class="w-32 h-32 rounded-2xl object-cover border border-zinc-700"
      />
    </div>

    <div class="flex-1 flex flex-col justify-center gap-1">
      <div>
        <h3 class="text-xl font-bold tracking-tight">Lo-fi</h3>
      </div>

      <div class="mt-3">
        <div class="relative h-1.5 w-full bg-foreground rounded-full overflow-hidden">
          <div 
            class="absolute h-full bg-background transition-all duration-300 shadow-[0_0_10px_rgba(249,115,22,0.5)]"
            :style="{ width: `${progress}%` }"
          ></div>
        </div>
      </div>

      <div class="flex items-center justify-between mt-2 gap-2">
        <Button variant="secondary" size="icon" class="h-9 w-12 bg-zinc-800/80 hover:bg-zinc-700 rounded-xl">
          <SkipBack class="w-5 h-5 fill-white" />
        </Button>

        <Button 
          @click="togglePlay"
          class="h-10 flex-1 bg-foreground rounded-xl transition-transform active:scale-95"
        >
          <component :is="isPlaying ? Pause : Play" class="w-6 h-6 fill-background" />
        </Button>

        <Button variant="secondary" size="icon" class="h-9 w-12 bg-zinc-800/80 hover:bg-zinc-700 rounded-xl">
          <SkipForward class="w-5 h-5 fill-white"/>
        </Button>

        <Button variant="ghost" size="icon" class="h-9 w-9 text-zinc-500 hover:text-white">
          <RotateCcw class="w-4 h-4" />
        </Button>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>