<script setup lang="ts">
import AppSidebar from '@/components/AppSidebar.vue';
import { SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import DialogCatalog from '@/components/pomodoro/DialogCatalog.vue';
import Button from '@/components/ui/button/Button.vue';
import { useTimerStore } from '@/stores/TimerStore';
import { LucideBookOpen, MenuIcon, Music4Icon } from 'lucide-vue-next';
import { ref } from 'vue';
import { useCardStore } from '@/stores/admin/CardStore';
import { useCatalogStore } from '@/stores/CatalogStore';
import Loading from '@/components/pomodoro/Loading.vue';
import DialogPlaylist from '@/components/audio/DialogPlaylist.vue';
import { usePlayerStore } from '@/stores/PlayerStore';
import { usePlaylistStore } from '@/stores/audio/PlaylistStore';
import PlayerAudio from '@/components/audio/PlayerAudio.vue';

const timerStore = useTimerStore();
const catalogStore = useCatalogStore();
const loading = ref(false);
const showCatalog = async () => {
    loading.value = !loading.value;
    await catalogStore.showCatalog();
    loading.value = !loading.value;
};
import 'vue-sonner/style.css';
import { Toaster } from '@/components/ui/sonner';

// PLAYLIST
const playlistStore = usePlaylistStore();
</script>

<template>
    <SidebarProvider :default-open="false">
        <Loading :is-loading="loading" />
        <AppSidebar />
        <main class="flex min-h-screen w-full flex-col p-5">
            <header class="flex h-20 w-full items-center justify-between">
                <SidebarTrigger as-child v-if="!timerStore.timer.start">
                    <Button class="h-10 w-10 border-2 border-primary-foreground"
                        ><MenuIcon
                    /></Button>
                </SidebarTrigger>

                <div
                    class="flex h-10 w-full items-start justify-end gap-5 self-end align-middle"
                >
                    <div
                        class="align-center flex flex-col items-end justify-center gap-1"
                    >
                        <Button
                            class="h-10 w-10 border-2 border-primary-foreground"
                            @click="playlistStore.toggleDialog"
                        >
                            <Music4Icon />
                        </Button>
                        <!-- <PlayerAudio /> -->
                    </div>
                    <Button
                        @click="showCatalog()"
                        class="h-10 w-10 border-2 border-primary-foreground"
                        v-if="!timerStore.timer.start"
                    >
                        <LucideBookOpen />
                    </Button>
                </div>
            </header>

            <section
                class="flex w-full flex-1 flex-col items-center justify-center gap-5"
            >
                <Toaster class="bg-foreground"/>

                <DialogCatalog />
                <DialogPlaylist />
                <slot />
            </section>
        </main>
    </SidebarProvider>
</template>
