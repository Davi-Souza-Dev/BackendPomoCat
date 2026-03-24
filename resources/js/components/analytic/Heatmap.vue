<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Calendar from '../ui/calendar/Calendar.vue';
interface Props{
    heatDates: string[]
}

const props = defineProps<Props>();

</script>

<template>
    <Card class="h-full w-full">
        <CardHeader>
            <CardTitle>Sequência de usos diários</CardTitle>
            <CardDescription
                >Você mantem uma sequência de: 2 dias</CardDescription
            >
        </CardHeader>
        <CardContent>
            <div>
                <Calendar
                    class="w-full"
                    :heat-dates="props.heatDates"
                    layout="month-and-year"
                    locale="pt-BR"
                />
            </div>
        </CardContent>
    </Card>
</template>
