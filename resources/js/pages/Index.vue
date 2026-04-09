<script setup lang="ts">
import Timer from '@/components/pomodoro/Timer.vue'
import AppLayout from '@/layout/AppLayout.vue'
import { useTimerStore } from '@/stores/TimerStore'
import { useUserStore } from '@/stores/UserStore'
const timerStore = useTimerStore()

interface Props{
  todayfocus: number;
}

const props = defineProps<Props>();
const userStore = useUserStore();
userStore.todayfocus = props.todayfocus;
</script>

<template>
  <AppLayout>
    <h1 class="font-extrabold" v-show="timerStore.timer.start == false">{{ userStore.todayfocus }} min de foco hoje!</h1>
    <Timer/>
  </AppLayout>
</template>
