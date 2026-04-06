import { usePlaylistStore } from '@/stores/audio/PlaylistStore';
import { Audio } from '@/types';
import { ref } from 'vue';
import { toast } from 'vue-sonner';

const sound = ref<Howl | null>(null);
const duration = ref(0);
const currentTime = ref(0);
const isPlaying = ref(false);
let rafId: number | null = null;
const title = ref('');
const muted = ref(false);
const volume = ref(0.5);

export function useAudioPlayer() {
const playlistStore = usePlaylistStore();

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
                    trackProgress();
                },
                onend() {
                    stopTracking();
                    isPlaying.value = false;
                    currentTime.value = 0;
                    nextAudio();
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

    const deleteAudio = async (audio: Audio) => {
        playlistStore.deleteAudio(audio);
    };

    const startPlaylist = () =>{
        handleAudio(playlistStore.playlist[0]);
    }

    return {
        handleAudio,
        togglePlay,
        nextAudio,
        prevAudio,
        toggleMute,
        setVolume,
        deleteAudio,
        startPlaylist,
        title,
        sound,
        isPlaying,
        currentTime,
        duration,
        volume,
        muted
    };
}
