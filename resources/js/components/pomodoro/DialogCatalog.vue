<script setup lang="ts">
import { Button } from '@/components/ui/button'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog'
import { BookOpen } from 'lucide-vue-next'
import CardCat from './CardCat.vue'
import { useCardStore } from '@/stores/CardStore'
import { useCatalogStore } from '@/stores/CatalogStore'

const emits = defineEmits(['update:open'])
const cardStore = useCardStore();
const catalogStore = useCatalogStore();
</script>

<template>
  <Dialog @update:open="catalogStore.active = false" v-model:open="catalogStore.active ">
    <DialogContent class="sm:max-w-[425px] bg-background p-0">
      <DialogHeader class="w-full flex flex-row gap-3 p-3">
        <DialogTitle>
          <div
            class="w-10 h-10 bg-background-app flex align-middle justify-center items-center rounded"
          >
            <BookOpen />
          </div>
        </DialogTitle>
        <DialogDescription class="flex justify-start flex-col text-left">
          <span class="text-l font-extrabold">Coleção</span>
          <span>{{catalogStore.totalCards}} cartas</span>
        </DialogDescription>
      </DialogHeader>

      <div
        class="w-full max-h-100 bg-background-dialog p-2 flex flex-row flex-wrap gap-10 overflow-y-auto justify-center"
      >
        <CardCat v-for="(card,index) in catalogStore.cards" :key="index" :index="index" :image="card.image_url" :title="card.title" :rarity="card.rarity"/>
      </div>

      <DialogFooter>
        <span class="w-full text-xs text-center flex justify-center items-center align-middle font-extrabold ">Conclua ciclos de 50 minutos para desbloquear novas cartas</span>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
