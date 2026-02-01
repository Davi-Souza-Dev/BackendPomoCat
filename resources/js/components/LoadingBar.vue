<script setup lang="ts">
import { AlertDialog, AlertDialogContent } from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import {
    Empty,
    EmptyContent,
    EmptyDescription,
    EmptyHeader,
    EmptyMedia,
    EmptyTitle,
} from '@/components/ui/empty';
import { Spinner } from '@/components/ui/spinner';
import { computed, ref, watch } from 'vue';
interface Props{
  loading: boolean,
}

const props = defineProps<Props>();
const loading = ref(props.loading );
watch(props, (newState) => {
  loading.value = newState.loading;
});

</script>

<template>
    <AlertDialog :open="loading">
        <AlertDialogContent>
            <Empty class="w-full">
                <EmptyHeader>
                    <EmptyMedia variant="icon">
                        <Spinner />
                    </EmptyMedia>
                    <EmptyTitle>Carregando...</EmptyTitle>
                </EmptyHeader>
            </Empty>
        </AlertDialogContent>
    </AlertDialog>
</template>