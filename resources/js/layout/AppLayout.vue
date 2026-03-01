<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue'
import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar'
import DialogCatalog from '@/components/pomodoro/DialogCatalog.vue'
import Button from '@/components/ui/button/Button.vue'
import { useTimerStore } from '@/stores/TimerStore'
import { LucideBookOpen, MenuIcon } from 'lucide-vue-next'
import { ref } from 'vue'
import { useCardStore } from '@/stores/admin/CardStore'
import { useCatalogStore } from '@/stores/CatalogStore'
import Loading from '@/components/pomodoro/Loading.vue'

const timerStore = useTimerStore()
const catalogStore = useCatalogStore();
const loading = ref(false);
const showCatalog = async () =>{
  loading.value = !loading.value
  await catalogStore.showCatalog()
  loading.value = !loading.value
}

</script>

<template>
  <SidebarProvider :default-open="false">
    <Loading :is-loading="loading"/>
    <AppSidebar />
    <main class="w-full min-h-screen p-5 flex flex-col">
      
      <header class="w-full h-20 flex justify-between items-center">
        <SidebarTrigger as-child v-if="!timerStore.timer.start">
          <Button class="w-10 h-10 border-2 border-primary-foreground"><MenuIcon /></Button>
        </SidebarTrigger>
        
        <Button
          @click="showCatalog()"
          class="w-10 h-10 border-2 border-primary-foreground"
          v-if="!timerStore.timer.start"
        >
          <LucideBookOpen />
        </Button>
      </header>

      <section class="flex-1 w-full flex flex-col justify-center items-center gap-5">
        <slot />
      </section>

    </main>
  </SidebarProvider>
</template>