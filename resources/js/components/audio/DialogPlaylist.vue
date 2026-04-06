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
import Input from '../ui/input/Input.vue';
import draggable from 'vuedraggable';
import { useAudioPlayer } from '@/composables/useAudioPlayer';
const dialogAudio = useDialogAudio();
const { 
    handleAudio, 
    togglePlay, 
    nextAudio, 
    prevAudio, 
    deleteAudio,
    currentTime,
    title,
    duration,
    sound,
    volume,
    toggleMute,
    setVolume,
    muted,
    isPlaying 
} = useAudioPlayer();

onMounted(() => {
    playlistStore.getPlaylist();
});

const dragList = computed({
    get: () => playlistStore.playlist,
    set: (value) => {
        playlistStore.updatePlaylistOrder(value);
    },
});



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

            <draggable
                v-model="dragList"
                item-key="order"
                tag="div"
                class="grid flex-1 auto-rows-min gap-6 overflow-y-auto px-4 pb-50"
                ghost-class="opacity-50"
                handle=".drag-handle"
                :animation="300"
            >
                <template #item="{ element }">
                    <AudioItem
                        :audio="element"
                        @play="handleAudio(element)"
                        @delete="deleteAudio(element)"
                    />
                </template>
            </draggable>

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
