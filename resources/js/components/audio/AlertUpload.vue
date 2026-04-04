<script setup lang="ts">
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { useDialogAudio } from '@/stores/audio/DialogUplaodAudio';
const dialogAudio = useDialogAudio();
import { UploadCloud } from 'lucide-vue-next';
import Progress from '../ui/progress/Progress.vue';
</script>

<template>
    <AlertDialog v-bind:open="dialogAudio.alert">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle
                    class="flex items-center justify-center"
                >{{ dialogAudio.progrees > 0 && dialogAudio.progrees < 100 ? "Enviando audio!" : "Audio Enviado!" }}</AlertDialogTitle>
                <AlertDialogDescription>
                </AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <Progress :modelValue="dialogAudio.progrees"/>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
