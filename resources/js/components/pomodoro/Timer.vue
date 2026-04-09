<script setup lang="ts">
import { PlayIcon, Settings, XIcon } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import { ButtonGroup } from '@/components/ui/button-group';
import { useTimerStore } from '@/stores/TimerStore';
import DialogConfig from './DialogConfig.vue';
import { ref } from 'vue';
import CentralIcon from './CentralIcon.vue';
import UnlockCard from './UnlockCard.vue';
const timerStore = useTimerStore();
const showDialogConfig = ref(false);
</script>
<template>
    <DialogConfig
        :open="showDialogConfig"
        @update:open="showDialogConfig = false"
    />
    <!-- <UnlockCard /> -->

    <div
        class="flex flex-col items-center justify-center gap-5 md:gap-10"
        v-if="timerStore.timer.start == false"
    >
        <CentralIcon />
        <h1
            class="text-6xl font-extrabold tracking-tighter uppercase md:text-7xl"
        >
            {{ String(timerStore.timer.min).padStart(2, '0') }}:{{
                String(timerStore.timer.sec).padStart(2, '0')
            }}
        </h1>

        <ButtonGroup class="transition-transform md:scale-125">
            <Button
                class="h-12 w-32 border-2 border-primary-foreground md:h-10 md:w-40"
                @click="timerStore.startTimer()"
            >
                <PlayIcon class="fill-foreground md:scale-125" />
            </Button>
            <Button
                class="h-12 w-12 border-2 border-foreground px-5 md:h-10 md:w-10"
                @click="showDialogConfig = true"
            >
                <Settings class="md:scale-125" />
            </Button>
        </ButtonGroup>

        <!-- <PlayerAudio/> -->
    </div>

    <div
        v-else
        class="flex flex-col items-center justify-center gap-5 md:gap-10"
    >
        <CentralIcon />

        <h1
            class="text-6xl font-extrabold tracking-tighter uppercase md:text-7xl"
        >
            {{ String(timerStore.timer.min).padStart(2, '0') }}:{{
                String(timerStore.timer.sec).padStart(2, '0')
            }}
        </h1>

        <ButtonGroup class="transition-transform md:scale-125">
            <Button
                class="h-12 w-32 border-2 border-primary-foreground md:h-10 md:w-50"
                @click="timerStore.startTimer()"
            >
                <XIcon class="fill-foreground md:scale-125" />
            </Button>
        </ButtonGroup>
    </div>
</template>
