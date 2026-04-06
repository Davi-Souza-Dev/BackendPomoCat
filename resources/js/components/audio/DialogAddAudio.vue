<script setup lang="ts">
import { ref, onUnmounted } from 'vue';
import { Howl } from 'howler';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Music, Upload, Play, Pause, X } from 'lucide-vue-next';
import Input from '../ui/input/Input.vue';
import Button from '../ui/button/Button.vue';
import {
  Field,
  FieldContent,
  FieldDescription,
  FieldError,
  FieldGroup,
  FieldLabel,
  FieldLegend,
  FieldSeparator,
  FieldSet,
  FieldTitle,
} from '@/components/ui/field'
import { useDialogAudio } from '@/stores/audio/DialogUplaodAudio';

const dialogAudio = useDialogAudio();
const file = ref<File | null>(null);
const objectUrl = ref<string | null>(null);
const isPlaying = ref(false);
const duration = ref(0);
const currentTime = ref(0);
let sound: Howl | null = null;
let progressInterval: ReturnType<typeof setInterval> | null = null;

const stopProgressTracker = () => {
    if (progressInterval) {
        clearInterval(progressInterval);
        progressInterval = null;
    }
};

const destroySound = () => {
    stopProgressTracker();
    sound?.unload();
    sound = null;
    if (objectUrl.value) {
        URL.revokeObjectURL(objectUrl.value);
        objectUrl.value = null;
    }
    isPlaying.value = false;
    duration.value = 0;
    currentTime.value = 0;
};

const handleAudio = (e: Event) => {
    const input = e.target as HTMLInputElement;
    const selected = input.files?.[0];
    if (!selected) return;

    destroySound();
    file.value = selected;
    dialogAudio.audio.file = file.value;
    objectUrl.value = URL.createObjectURL(selected);
    dialogAudio.audio.title = file.value.name.split('.')[0];

    sound = new Howl({
        src: [objectUrl.value],
        format: ['mp3'],
        html5: true,
        onload() {
            duration.value = sound?.duration() ?? 0;
        },
        onend() {
            isPlaying.value = false;
            currentTime.value = 0;
            stopProgressTracker();
        },
    });
};

const togglePlay = () => {
    if (!sound) return;

    if (isPlaying.value) {
        sound.pause();
        isPlaying.value = false;
        stopProgressTracker();
    } else {
        sound.play();
        isPlaying.value = true;
        progressInterval = setInterval(() => {
            currentTime.value = (sound?.seek() as number) ?? 0;
        }, 500);
    }
};

const seekTo = (e: Event) => {
    const input = e.target as HTMLInputElement;
    const value = Number(input.value);
    sound?.seek(value);
    currentTime.value = value;
};

const clearFile = () => {
    destroySound();
    duration.value = 0;
    dialogAudio.audio.title = "";
    file.value = null;
};

const setAudio = async () =>{
    await dialogAudio.setAudio();
    clearFile();
}

onUnmounted(() => destroySound());
</script>

<template>
    <Dialog :open="dialogAudio.active" @update:open="dialogAudio.toggleDialog">
        <DialogContent class="sm:max-w-[400px]" aria-describedby="undefined">
            <DialogHeader>
                <DialogTitle>Adicionar música / áudio</DialogTitle>
                <DialogDescription>
                    Envie um arquivo .mp3 para salvar em sua playlist. Limite de tamanho 128 MB
                </DialogDescription>
            </DialogHeader>

            <div class="flex flex-col gap-4">
                <!-- upload -->
                <Label
                    v-if="file == null"
                    for="audio-upload"
                    class="border-muted-foreground/30 hover:bg-muted/30 flex cursor-pointer flex-col items-center justify-center gap-3 rounded-lg border-2 border-dashed p-8 transition-colors hover:border-primary/50"
                >
                    <div
                        class="bg-muted flex items-center justify-center rounded-full"
                    >
                        <Upload class="text-muted-foreground h-5 w-5" />
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium">
                            Clique para selecionar
                        </p>
                    </div>
                    <Input
                        id="audio-upload"
                        type="file"
                        accept=".mp3"
                        class="sr-only"
                        @change="handleAudio"
                    />
                </Label>

                <div
                    v-if="file"
                    class="bg-muted/30 flex flex-col gap-3 rounded-lg border p-4"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-primary/10"
                        >
                            <Music class="h-4 w-4 text-primary" />
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="truncate text-sm font-medium">
                                {{ file.name }}
                            </p>
                            <p class="text-muted-foreground text-xs">
                                {{ (file.size / 1024 / 1024).toFixed(2) }} MB
                            </p>
                        </div>
                        <Button
                            @click="clearFile"
                            class="hover:bg-muted text-muted-foreground flex h-7 w-7 items-center justify-center rounded-full transition-colors hover:text-foreground"
                        >
                            <X class="h-4 w-4" />
                        </Button>
                    </div>

                    <!-- Player -->
                    <div class="flex items-center gap-3">
                        <Button
                            @click="togglePlay"
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary text-primary-foreground transition-colors hover:bg-primary/90"
                        >
                            <Play v-if="!isPlaying" class="ml-0.5 h-4 w-4" />
                            <Pause v-else class="h-4 w-4" />
                        </Button>

                        <div class="flex flex-1 flex-col">
                            <Input
                                type="range"
                                :min="0"
                                :max="duration"
                                step="0.1"
                                :value="currentTime"
                                class="w-full cursor-pointer accent-primary"
                                @input="seekTo"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="file != null">
                    <FieldSet>
                        <FieldGroup>
                            <Field>
                                <FieldLabel for="name"> Nome do áudio </FieldLabel>
                                <Input
                                    id="name"
                                    autocomplete="off"
                                    placeholder="Lo-fi"
                                    v-model="dialogAudio.audio.title"
                                    class="capitalize"
                                />
                                <FieldDescription
                                    >Coloque um nome para exibição ou use o do arquivo por padrão.</FieldDescription
                                >
                            </Field>
                        </FieldGroup>
                    </FieldSet>
                </div>
            </div>

            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline" @click="clearFile"
                        >Cancelar</Button
                    >
                </DialogClose>
                <Button type="submit" :disabled="!file || dialogAudio.audio.title.length == 0" @click="setAudio">Salvar</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
