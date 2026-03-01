<script setup lang="ts">
import CardEstilo from '@/components/Dashboard/Encomendas/CardEstilo.vue';
import DialogEstilo from '@/components/Dashboard/Encomendas/DialogEstilo.vue';
import Empty from '@/components/Empty.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import Button from '@/components/ui/button/Button.vue';
import { useCardStore } from '@/stores/admin/CardStore';
import { CardType } from '@/types/types';
import { PlusCircleIcon } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast, Toaster } from 'vue-sonner';
const cardStore = useCardStore();

import CardCat from '@/components/catalog/CardCat.vue';
import DialogCard from '@/components/catalog/DialogCard.vue';
import AdminLayout from '@/layout/AdminLayout.vue';
interface Props {
    cards: CardType[];
}

const props = defineProps<Props>();
cardStore.cards = props.cards;
const showDialog = ref(false);
const showAlert = ref(false);

const setCard = (card: CardType) => {
    cardStore.card = card;
    console.log(cardStore.card);

    showDialog.value = true;
};

const loading = ref(false);

const deleteCard = async (id: number) => {
    loading.value = !loading.value;
    const response = await cardStore.delete(id);
    if (response.success) {
        cardStore.clear();
        showAlert.value = false;
        loading.value = !loading.value;
        toast.success(response.success.titulo);
    } else {
        cardStore.clear();
        showAlert.value = false;
        loading.value = !loading.value;
        toast.error(response.error.titulo);
    }
};
</script>

<template>
    <AdminLayout page="Catálogo">
        <DialogCard :open="showDialog" @close="showDialog = !showDialog" />
        <header class="menubar">
            <Button variant="outline" type="button" @click="showDialog = true">
                <PlusCircleIcon />
            </Button>
        </header>
        <div class="flex max-w-full flex-wrap gap-10 p-5">
            <CardCat
                v-for="(card, index) in cardStore.cards"
                :key="index"
                :card="card"
                @edit-card="setCard(card)"
                @delete="deleteCard"
            />
        </div>
    </AdminLayout>
</template>

<style lang="css" scoped>
.menubar {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: end;
    gap: 1rem;
    padding: 10px;
    border-radius: 5px;
}
</style>
