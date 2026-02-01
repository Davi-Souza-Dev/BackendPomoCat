<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { CardType } from '@/types/types';
import { Edit2Icon, Trash2Icon } from 'lucide-vue-next';

interface Props {
    card: CardType;
}

const props = defineProps<Props>();
const emit = defineEmits(['editCard','delete']);
</script>

<template>
    <Card class="h-fit w-full rounded-lg border text-sm sm:w-[300px]">
        <!-- Header com imagem -->
        <CardHeader class="p-0">
            <img
                :src="card.image_url"
                class="h-50 w-full rounded-sm object-cover"
            />
        </CardHeader>

        <div class="space-y-2 p-4">
            <CardTitle class="text-base">
                {{ card.title }}
            </CardTitle>

            <CardDescription
                class="line-clamp-2 text-sm leading-snug text-wrap"
            >
                {{ card.rarity }}
            </CardDescription>
        </div>

        <CardFooter
            class="flex items-center justify-between gap-2 px-4 pt-0 pb-4"
        >
            <Button size="sm" class="flex-1" @click="emit('editCard',card.id)">
                <Edit2Icon class="mr-1 h-4 w-4" />
                Editar
            </Button>

            <Button size="icon" variant="destructive" class="h-9 w-9" @click="emit('delete',props.card.id)">
                <Trash2Icon class="h-4 w-4" />
            </Button>
        </CardFooter>
    </Card>
</template>