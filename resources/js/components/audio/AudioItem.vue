<script lang="ts" setup>
import { Trash2 } from 'lucide-vue-next';
import Button from '../ui/button/Button.vue';
import { Audio } from '@/types';
import { ref } from 'vue';

interface Props {
    audio: Audio;
}
const props = defineProps<Props>();
const emits = defineEmits(['play','delete']);
</script>

<template>
    <div
        class="flex w-full items-start gap-3 rounded-lg border border-primary/60 bg-background-app/50 px-4 py-3 shadow-lg hover:border-2 hover:border-foreground"
        @click="emits('play')"
        >
        <div
            class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-lg bg-[#5c3a2a]"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-foreground"
                fill="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    d="M12 3v10.55A4 4 0 1 0 14 17V5h4V3h-6zm-2 16a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"
                />
            </svg>
        </div>
        <div
            class="flex w-full flex-col items-center justify-center align-middle"
        >
            <div class="flex w-full items-center justify-between align-middle">
                <span
                    class="flex items-center justify-center align-middle text-sm leading-tight font-semibold text-foreground capitalize"
                    >{{ props.audio.title }}</span
                >
                <Button variant="ghost" class="stroke-red-300" @click.stop="emits('delete')"
                    ><Trash2 />
                </Button>
            </div>
        </div>
    </div>
</template>
