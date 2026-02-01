<script setup lang="ts">
import LoadingBar from '@/components/LoadingBar.vue';
import Button from '@/components/ui/button/Button.vue';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
const emits = defineEmits(['close']);

// CONFIGURAÇÔES DO FORM
import { FieldGroup, FieldSet } from '@/components/ui/field';
import Field from '@/components/ui/field/Field.vue';
import FieldLabel from '@/components/ui/field/FieldLabel.vue';
import FieldSeparator from '@/components/ui/field/FieldSeparator.vue';
import FieldTitle from '@/components/ui/field/FieldTitle.vue';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { ref } from 'vue';
import { toast, Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';
import { useCardStore } from '@/Stores/CardStore';
import UploadImagem from './UploadImagem.vue';
import Input from '../ui/input/Input.vue';

interface Props {
    open: boolean;
}

const props = defineProps<Props>();
const cardStore = useCardStore();
const loading = ref(false);

const optionsRarity = [
    { value: 'comum' },
    { value: 'raro' },
    { value: 'epíco' },
    { value: 'lendário' },
];
const selectedRarity = ref('');

const setCard = async () => {
    loading.value = !loading.value;
    const response = await cardStore.setCard();
    if (response.success) {
        emits('close');
        loading.value = !loading.value;
        cardStore.clear();
        toast.success(response.success.titulo);
    } else {
        loading.value = !loading.value;
        toast.error(response.error.titulo);
    }
};

</script>

<template>
    <Dialog :open="props.open" @update:open="emits('close') ">
        <LoadingBar :loading="loading" />
        <Toaster />
        <DialogContent
            class="max-h-[90vh] max-w-[90vw] overflow-y-auto rounded-lg p-6 md:max-w-150"
        >
            <DialogHeader>
                <DialogTitle>{{
                    cardStore.card.id != 0 ? 'Editar Carta' : 'Criar Carta'
                }}</DialogTitle>
                <div>
                    <FieldGroup>
                        <FieldSet>
                            <FieldGroup>
                                <Field>
                                    <FieldLabel> Imagem </FieldLabel>
                                    <UploadImagem
                                        :imagem="cardStore.card.image_url"
                                    />
                                </Field>

                                <FieldGroup>
                                    <Field>
                                        <FieldLabel for="txtTitulo">
                                            Titulo
                                        </FieldLabel>
                                        <Input
                                            id="txtTitulo"
                                            v-model="cardStore.card.title"
                                        />
                                    </Field>
                                    <FieldLabel for="txtNome">
                                        Raridade Da Carta
                                    </FieldLabel>
                                    <div>
                                        <Select
                                            name="slEtapa"
                                            v-model="cardStore.card.rarity"
                                        >
                                            <SelectTrigger
                                                class="h-8 w-50 px-2"
                                            >
                                                <SelectValue />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem
                                                    v-for="option in optionsRarity"
                                                    :key="option.value"
                                                    :value="option.value"
                                                    class="capitalize"
                                                >
                                                    {{ option.value }}
                                                </SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <!-- <FieldSeparator />
                                    <Field>
                                        <FieldTitle>Configurações</FieldTitle>
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <Switch
                                                id="swAtiva"
                                                v-model="
                                                    cardStore.card.estilo.active
                                                "
                                            />

                                            <Label for="swAtiva">Ativa</Label>
                                            <TooltipProvider>
                                                <Tooltip>
                                                    <TooltipTrigger as-child>
                                                        <Button
                                                            variant="outline"
                                                        >
                                                            <InfoIcon />
                                                        </Button>
                                                    </TooltipTrigger>
                                                    <TooltipContent
                                                        class="w-80"
                                                    >
                                                        <p>
                                                            Define se o estilo
                                                            ira aparecer para o
                                                            cliente
                                                        </p>
                                                    </TooltipContent>
                                                </Tooltip>
                                            </TooltipProvider>
                                        </div>
                                    </Field> -->
                                </FieldGroup>
                            </FieldGroup>
                        </FieldSet>
                        <FieldSeparator />
                    </FieldGroup>
                </div>
            </DialogHeader>
            <Button variant="secondary" @click="setCard"> Salvar </Button>
            <Button @click="(cardStore.clear(), emits('close'))">
                Cancel
            </Button>
        </DialogContent>
    </Dialog>
</template>

<style scoped>
.dialog {
    width: 300px;
}
</style>
