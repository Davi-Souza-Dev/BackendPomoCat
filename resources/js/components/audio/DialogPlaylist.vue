<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetDescription,
    SheetFooter,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/components/ui/sheet';
import { usePlaylistStore } from '@/stores/audio/PlaylistStore';
import { PlusSquare, PlusSquareIcon } from 'lucide-vue-next';
import AudioItem from './AudioItem.vue';
import { Play, Pause, SkipBack, SkipForward, RotateCcw } from 'lucide-vue-next';
const playlistStore = usePlaylistStore();
import DialogAddAudio from './DialogAddAudio.vue';
import { useDialogAudio } from '@/stores/audio/DialogUplaodAudio';
import AlertUpload from './AlertUpload.vue';
import { onMounted, ref } from 'vue';
import { Audio } from '@/types';
import Input from '../ui/input/Input.vue';
const dialogAudio = useDialogAudio();
onMounted(() => {
    playlistStore.getPlaylist();
});

let sound: Howl | null = null;
let progressInterval: ReturnType<typeof setInterval> | null = null;
const duration = ref(0);
const currentTime = ref(0);
const isPlaying = ref(false);

const handleAudio = (audio: Audio) => {
    sound = new Howl({
        src: audio.path ?? '',
        format: ['mp3'],
        html5: true,
        onload() {
            duration.value = sound?.duration() ?? 0;
        },
        onend() {
            isPlaying.value = false;
            currentTime.value = 0;
        },
    });
};
const togglePlay = () => {
    if (!sound) return;

    if (isPlaying.value) {
        sound.pause();
        isPlaying.value = false;
    } else {
        sound.play();
        isPlaying.value = true;
        progressInterval = setInterval(() => {
            currentTime.value = (sound?.seek() as number) ?? 0;
        }, 100);
    }
};
</script>

<template>
    <DialogAddAudio />
    <AlertUpload />
    <Sheet
        v-model:open="playlistStore.active"
        @update:open="
            (state) => {
                playlistStore.active = state;
            }
        "
    >
        <SheetContent>
            <SheetHeader>
                <SheetTitle>Playlist</SheetTitle>
            </SheetHeader>

            <div
                class="grid flex-1 auto-rows-min gap-6 overflow-y-auto px-4 pb-50"
            >
                <AudioItem
                    v-for="(audio, index) in playlistStore.playlist"
                    :key="index"
                    :audio="audio"
                    @play="handleAudio(audio)"
                />
            </div>

            <SheetFooter class="absolute bottom-0 w-full rounded-t-lg bg-card">
                <div class="flex flex-1 flex-col justify-center gap-1">
                    <div class="mt-3">
                        <div
                            class="relative h-1.5 w-full overflow-hidden rounded-full bg-foreground"
                        >
                            <Input
                                type="range"
                                :min="0"
                                :max="duration"
                                step="0.1"
                                :value="currentTime"
                                class="w-full cursor-pointer accent-primary"
                            />
                        </div>
                    </div>

                    <div class="mt-2 flex items-center justify-between gap-2">
                        <Button
                            variant="secondary"
                            size="icon"
                            class="h-9 w-12 rounded-xl bg-primary/80 hover:bg-primary"
                        >
                            <SkipBack class="h-5 w-5 fill-white" />
                        </Button>

                        <Button
                            class="h-10 flex-1 rounded-xl bg-foreground transition-transform hover:bg-primary active:scale-95"
                        >
                            <component
                                :is="isPlaying ? Pause : Play"
                                class="h-6 w-6 fill-background"
                            />
                        </Button>

                        <Button
                            variant="secondary"
                            size="icon"
                            class="h-9 w-12 rounded-xl bg-primary/80 hover:bg-primary"
                        >
                            <SkipForward class="h-5 w-5 fill-white" />
                        </Button>
                    </div>
                </div>
                <Button
                    @click="
                        (playlistStore.toggleDialog(),
                        dialogAudio.toggleDialog())
                    "
                >
                    <PlusSquare /> Adicionar Música
                </Button>
            </SheetFooter>
        </SheetContent>
    </Sheet>
</template>
