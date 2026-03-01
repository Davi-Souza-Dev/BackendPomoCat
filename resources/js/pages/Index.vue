<script setup lang="ts">
import CentralIcon from '@/components/pomodoro/CentralIcon.vue'
import DialogCatalog from '@/components/pomodoro/DialogCatalog.vue'
import Timer from '@/components/pomodoro/Timer.vue'
import AppLayout from '@/layout/AppLayout.vue'
import { useTimerStore } from '@/stores/TimerStore'
import { useUserStore } from '@/stores/UserStore'
import { User } from '@/types'
import { ref } from 'vue'
const timerStore = useTimerStore()

interface Props{
  user: User
}
const props = defineProps<Props>();
const userStore = useUserStore();
userStore.user = props.user;
</script>

<template>
  <AppLayout>
    <DialogCatalog/>
    <!-- <h1 class="font-extrabold" v-show="timerStore.timer.start == false">0 gatos descobertos hoje</h1> -->
    <Timer/>
  </AppLayout>
</template>
