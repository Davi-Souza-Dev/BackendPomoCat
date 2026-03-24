<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Play, Pause, SkipBack, SkipForward, RotateCcw } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
const isPlaying = ref(false);
const progress = ref(10);

import videojs from 'video.js';
import 'videojs-youtube';
import 'video.js/dist/video-js.css';
const videoPlayer = ref<HTMLAudioElement | string>('');
let player: any = null;

const videoOptions = {
    techOrder: ['youtube'],
    options: {
        audioOnlyMode: true,
        controls: false,
        loop: true,
    },
    sources: [
        {
            type: 'video/youtube',
            src: 'https://www.youtube.com/watch?v=BTST5HyO8gs&list=WL&index=4&t=2739s',
        },
    ],
    // youtube: { customVars: { wmode: 'transparent' } },
};

onMounted(() => {
    player = videojs(videoPlayer.value, videoOptions, () => {
        console.log('Player pronto!');
    });
    player.ready(()=>{
      player.play()
    });
    player.on('timeupdate', () => {
        const currentTime = player.currentTime();
        const duration = player.duration(); 
        console.log(progress);
    });
});

const togglePlay = () => {
    console.log(player);
    player.play();
    isPlaying.value = !isPlaying.value;
};
</script>

<template>
    <div
        class="border-fore flex w-full max-w-100 items-center gap-5 rounded-sm border bg-background-dialog p-4 text-white shadow-2xl"
    >
        <!-- <div class="relative shrink-0">
      <img 
        src="https://upload.wikimedia.org/wikipedia/commons/6/6a/Johann_Sebastian_Bach.jpg" 
        alt="Bach"
        class="w-32 h-32 rounded-2xl object-cover border border-zinc-700"
      />
    </div> -->

        <div class="flex flex-1 flex-col justify-center gap-1">
            <div>
                <h3 class="text-xl font-bold tracking-tight">Lo-fi</h3>
            </div>

            <div class="mt-3">
                <div
                    class="relative h-1.5 w-full overflow-hidden rounded-full bg-foreground"
                >
                    <div
                        class="absolute h-full bg-background shadow-[0_0_10px_rgba(249,115,22,0.5)] transition-all duration-300"
                        :style="{ width: `${progress}%` }"
                    ></div>
                </div>
            </div>

            <div class="mt-2 flex items-center justify-between gap-2">
                <Button
                    variant="secondary"
                    size="icon"
                    class="h-9 w-12 rounded-xl bg-zinc-800/80 hover:bg-zinc-700"
                >
                    <SkipBack class="h-5 w-5 fill-white" />
                </Button>

                <Button
                    @click="togglePlay"
                    class="h-10 flex-1 rounded-xl bg-foreground transition-transform active:scale-95"
                >
                    <component
                        :is="isPlaying ? Pause : Play"
                        class="h-6 w-6 fill-background"
                    />
                </Button>

                <Button
                    variant="secondary"
                    size="icon"
                    class="h-9 w-12 rounded-xl bg-zinc-800/80 hover:bg-zinc-700"
                >
                    <SkipForward class="h-5 w-5 fill-white" />
                </Button>

                <Button
                    variant="ghost"
                    size="icon"
                    class="h-9 w-9 text-zinc-500 hover:text-white"
                >
                    <RotateCcw class="h-4 w-4" />
                </Button>
            </div>
        </div>
    </div>
    <div class="hidden">
        <audio
            ref="videoPlayer"
            class="video-js vjs-big-play-centered vjs-theme-city hidden"
        ></audio>
    </div>
</template>

<style scoped></style>
