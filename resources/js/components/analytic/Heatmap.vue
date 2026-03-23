<script setup lang="ts">
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Calendar from '../ui/calendar/Calendar.vue';
import { ref } from 'vue';
import { DateValue } from 'reka-ui';
import { getLocalTimeZone, today } from '@internationalized/date';

const selectedDates = ref<DateValue[]>();
const currentToday = today(getLocalTimeZone());
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
                    :heat-dates="['2026-03-24']"
                    layout="month-and-year"
                    locale="pt-BR"
                />
            </div>
        </CardContent>
    </Card>
</template>
