<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { useDialogAudio } from '@/stores/audio/DialogUplaodAudio';
const dialogAudio = useDialogAudio();
import Progress from '../ui/progress/Progress.vue';
</script>

<template>
    <AlertDialog v-bind:open="dialogAudio.alert" >
        <AlertDialogContent class="border-foreground border-2">
            <AlertDialogHeader>
                <AlertDialogTitle
                    class="flex items-center justify-center"
                >{{ dialogAudio.progrees > 0 && dialogAudio.progrees < 100 ? "Enviando audio!" : "Audio Enviado!" }}</AlertDialogTitle>
                <AlertDialogDescription>
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <Progress :modelValue="dialogAudio.progrees" class="bg-foreground p-0 m-0"/>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
