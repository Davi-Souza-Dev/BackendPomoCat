<script setup lang="ts">
import api from '@/api'
import {
  Dialog,
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from '@/components/ui/dialog'

const timerStore = useTimerStore()

const emits = defineEmits(['update:open'])
interface Props {
  open: boolean
}

const props = defineProps<Props>() 

import {
  Field,
  FieldContent,
  FieldDescription,
  FieldGroup,
  FieldLabel,
  FieldSet,
  FieldTitle,
} from '@/components/ui/field'
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group'
import { useTimerStore } from '@/stores/TimerStore'
import { Settings } from 'lucide-vue-next'
import { onBeforeMount } from 'vue'

</script>

<template>
  <Dialog @update:open="emits('update:open')" v-model:open="props.open">
    <DialogContent>
      <DialogHeader>
        <DialogTitle class="flex align-middle items-center gap-1"
          ><Settings />Configurações</DialogTitle
        >
        <DialogDescription>
          <div class="w-full max-w-md">
            <FieldGroup>
              <FieldSet>
                <FieldLabel for="compute-environment-p8w"> Timer </FieldLabel>
                <RadioGroup v-model="timerStore.timer.min">
                  <FieldLabel for="t25">
                    <Field orientation="horizontal">
                      <FieldContent>
                        <FieldTitle class="text-2xl">25:00</FieldTitle>
                        <FieldDescription>
                          Tempo rápido para tarefas simples. Complete 2 desses para desbloquear uma
                          carta!.
                        </FieldDescription>
                      </FieldContent>
                      <RadioGroupItem id="t25" :value="25" />
                    </Field>
                  </FieldLabel>
                  <FieldLabel for="t50">
                    <Field orientation="horizontal">
                      <FieldContent>
                        <FieldTitle class="text-2xl">50:00</FieldTitle>
                        <FieldDescription>
                          Tempo padrão para estudos e demais tarefas. Complete 1 desses para
                          desbloquear uma carta!.
                        </FieldDescription>
                      </FieldContent>
                      <RadioGroupItem id="t50" :value="50" />
                    </Field>
                  </FieldLabel>
                </RadioGroup>
              </FieldSet>
            </FieldGroup>
          </div>
        </DialogDescription>
      </DialogHeader>
    </DialogContent>
  </Dialog>
</template>
