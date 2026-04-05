<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetFooter,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { usePlaylistStore } from '@/stores/audio/PlaylistStore';
import { PlusSquare, Volume2, VolumeX } from 'lucide-vue-next';
import AudioItem from './AudioItem.vue';
import { Play, Pause, SkipBack, SkipForward } from 'lucide-vue-next';
const playlistStore = usePlaylistStore();
import DialogAddAudio from './DialogAddAudio.vue';
import { useDialogAudio } from '@/stores/audio/DialogUplaodAudio';
import AlertUpload from './AlertUpload.vue';
import { computed, onMounted, ref } from 'vue';
import { Audio } from '@/types';
import Input from '../ui/input/Input.vue';
import { toast } from 'vue-sonner';
const dialogAudio = useDialogAudio();
onMounted(() => {
    playlistStore.getPlaylist();
});

const sound = ref<Howl | null>(null);
const duration = ref(0);
const currentTime = ref(0);
const isPlaying = ref(false);
let rafId: number | null = null;
const title = ref('');
const muted = ref(false);
const volume = ref(0.5);

const handleAudio = (audio: Audio) => {
    try {
        sound.value?.unload();
        sound.value = null;
        sound.value = new Howl({
            src: [audio.url ?? ''],
            format: ['mp3'],
            html5: true,
            onload() {
                duration.value = sound.value?.duration() ?? 0;
                isPlaying.value = true;
                title.value = audio.title;
                playlistStore.actualAudio = audio;
                console.log(playlistStore.actualAudio)
                trackProgress();
            },
            onend() {
                stopTracking();
                isPlaying.value = false;
                currentTime.value = 0;
            },
            onplayerror() {
                toast.error('Erro ao iniciar áudio');
            },
        });
        sound.value.play();
        Howler.volume(volume.value);
    } catch (error) {
        console.info(error);
        toast.error('Erro ao iniciar áudio');
    }
};

const trackProgress = () => {
    if (!sound.value || !isPlaying.value) return;
    currentTime.value = sound.value.seek() as number;
    rafId = requestAnimationFrame(trackProgress);
};

const stopTracking = () => {
    if (rafId) {
        cancelAnimationFrame(rafId);
        rafId = null;
    }
};

const togglePlay = () => {
    if (!sound.value) return;
    if (isPlaying.value) {
        sound.value.pause();
        isPlaying.value = false;
        stopTracking();
    } else {
        sound.value.play();
        isPlaying.value = true;
        trackProgress();
    }
};

const nextAudio = () => {
    const actualAudioIndex = playlistStore.playlist.findIndex(
        (audio: Audio) => audio.id == playlistStore.actualAudio.id,
    );
    const length = playlistStore.playlist.length;
    if (actualAudioIndex + 1 == length) {
        return toast.error('Sem mais áudios!');
    }
    const next = playlistStore.playlist[actualAudioIndex + 1];
    handleAudio(next);
};

const prevAudio = () => {  
   const actualAudioIndex = playlistStore.playlist.findIndex(
        (audio: Audio) => audio.id == playlistStore.actualAudio.id,
    );
    if (actualAudioIndex <= 0) {
        return toast.error('Sem mais áudios!');
    }
    
    const prev = playlistStore.playlist[actualAudioIndex - 1];
    handleAudio(prev);
};

const toggleMute = () => {
    if (!sound.value) return;
    muted.value = !muted.value;
    sound.value.mute(muted.value);
};

const setVolume = () => {
    if (!sound.value) return;
    Howler.volume(volume.value);
};

const deleteAudio = async (audio:Audio) =>{
    playlistStore.deleteAudio(audio)
}
</script>

<template>
    <DialogAddAudio />
    <AlertUpload />
    <Sheet
        v-model:open="playlistStore.active"
        @update:open="(state) => (playlistStore.active = state)"
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
                    @delete="deleteAudio(audio)"
                />
            </div>

            <SheetFooter class="absolute bottom-0 w-full rounded-t-lg bg-card">
                <div
                    class="flex flex-1 flex-col justify-center gap-1"
                    v-if="sound"
                >
                    <h1 class="font-extrabold capitalize">{{ title }}</h1>
                    <div class="flex-flex flex flex-1">
                        <Input
                            id="track"
                            type="range"
                            :min="0"
                            :max="duration || 10"
                            step="any"
                            :value="currentTime || 0"
                            v-model="currentTime"
                            class="range-track m-0 w-full cursor-pointer p-0 accent-primary"
                        />
                        <div class="group relative flex flex-col items-center">
                            <div
                                class="pointer-events-none absolute bottom-10 mb-2 opacity-0 transition-all group-hover:pointer-events-auto group-hover:opacity-100"
                            >
                                <Input
                                    type="range"
                                    name="rSoung"
                                    :min="0"
                                    :max="1"
                                    step="0.01"
                                    :value="volume ?? 0.01"
                                    v-model="volume"
                                    class="range-track m-0 w-20 -rotate-90 cursor-pointer p-0 accent-primary"
                                    @input="setVolume"
                                />
                            </div>

                            <Button
                                variant="ghost"
                                size="icon"
                                @click="toggleMute"
                            >
                                <Volume2
                                    v-if="!muted && volume > 0"
                                    class="h-4 w-4"
                                />
                                <VolumeX v-else class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>

                    <div class="mt-2 flex items-center justify-between gap-2">
                        <Button
                            variant="secondary"
                            size="icon"
                            class="h-9 w-12 rounded-xl bg-primary/80 hover:bg-primary"
                            @click="prevAudio"
                        >
                            <SkipBack class="h-5 w-5 fill-white" />
                        </Button>

                        <Button
                            class="h-10 flex-1 rounded-xl bg-foreground transition-transform hover:bg-primary active:scale-95"
                            @click="togglePlay()"
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
                            @click="nextAudio"
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

<style scoped></style>
