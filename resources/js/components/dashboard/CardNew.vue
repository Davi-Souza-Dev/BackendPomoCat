<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { PlusIcon } from 'lucide-vue-next';

</script>

<template>
    <Button class="group relative flex min-h-[360px] w-full flex-col items-center justify-center rounded-xl border-2 border-dashed bg-card p-6 text-center transition-all hover:border-primary/50 hover:bg-muted/50 sm:w-[250px]">
        
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-muted transition-colors group-hover:bg-primary/10">
            <PlusIcon class="h-8 w-8 text-muted-foreground transition-colors group-hover:text-primary" />
        </div>

        <div class="space-y-1">
            <h3 class="text-lg font-bold tracking-tight text-foreground">
                Adicionar Card
            </h3>
        </div>

        <div class="absolute inset-0 rounded-xl bg-gradient-to-b from-transparent to-primary/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
    </Button>
</template>