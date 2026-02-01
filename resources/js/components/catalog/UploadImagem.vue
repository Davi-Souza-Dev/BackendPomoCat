<script setup lang="ts">
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { useCardStore } from '@/Stores/CardStore';
import { UploadCloud } from 'lucide-vue-next';
import { ref, watch } from 'vue';
const cardStore = useCardStore();
const props = defineProps(['imagem']);
const file = ref<File | null>(null);
const previewUrl = ref<string | null>(props.imagem);

function handleFileChange(e: Event) {
    const input = e.target as HTMLInputElement;
    if (!input.files?.length) return;

    file.value = input.files[0];
    cardStore.file = file.value;
    if (file.value.type.startsWith('image/')) {
        previewUrl.value = URL.createObjectURL(file.value);
    }
}

watch(
    () => props.imagem,
    (novaImg) => {
        previewUrl.value = novaImg || null;
    },
    { immediate: true }
);
</script>

<template>
    <div class="flex items-start gap-3">
        <div
            class="relative h-32 w-32 overflow-hidden rounded-lg border bg-muted/30 shadow-sm"
        >
            <!-- Imagem -->
            <img
                v-if="previewUrl"
                :src="previewUrl"
                :alt="file?.name"
                class="h-full w-full object-cover"
            />
        </div>
        <Label
            for="upload-input"
            class="flex h-20 w-20 cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-muted-foreground transition hover:bg-muted/30"
        >
            <UploadCloud class="mb-0.5 size-5 text-muted-foreground" />
            <p class="text-[10px] leading-none text-muted-foreground">
                Enviar imagem
            </p>

            <Input
                id="upload-input"
                type="file"
                class="hidden"
                @change="handleFileChange"
                accept="image/png, image/jpeg"
            />
        </Label>
    </div>
</template>