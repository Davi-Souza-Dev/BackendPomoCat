import { ref } from 'vue';
import videojs from 'video.js';
import { Howl } from 'howler';

type SourceType = 'file' | 'youtube';

export function useAudioPlayer() {
    let howl: Howl | null = null;
    let vjsPlayer: any = null;
    const isPlaying = ref(false);
    const progress = ref(0);

    const loadFile = (src: string) => {
        // Limpa o player anterior
        vjsPlayer?.dispose();
        vjsPlayer = null;

        howl = new Howl({
            src: [src],
            html5: true,        // essencial para arquivos grandes
            onplay: () => isPlaying.value = true,
            onpause: () => isPlaying.value = false,
            onend: () => isPlaying.value = false,
        });
    };

    const loadYoutube = (element: HTMLVideoElement, src: string) => {
        // Limpa o player anterior
        howl?.unload();
        howl = null;

        vjsPlayer = videojs(element, {
            techOrder: ['youtube'],
            sources: [{ type: 'video/youtube', src }],
        });
    };

    const play = () => {
        howl ? howl.play() : vjsPlayer?.play();
        isPlaying.value = true;
    };

    const pause = () => {
        howl ? howl.pause() : vjsPlayer?.pause();
        isPlaying.value = false;
    };

    const getProgress = (): number => {
        if (howl) {
            return ((howl.seek() as number) / howl.duration()) * 100;
        }
        if (vjsPlayer) {
            return (vjsPlayer.currentTime() / vjsPlayer.duration()) * 100;
        }
        return 0;
    };

    return { play, pause, isPlaying, progress, loadFile, loadYoutube, getProgress };
}