<script lang="ts" setup>
import Heatmap from '@/components/analytic/Heatmap.vue';
import BarChart from '@/components/analytic/BarChart.vue';
import Streakbar from '@/components/analytic/Streakbar.vue';
import AppLayout from '@/layout/AppLayout.vue';

interface Props{
    heatDates: string[],
    week:{
        title: string,
        data: number[],
        total: number
    }
}

const props = defineProps<Props>();
</script>

<template>
    <AppLayout>
        <div class="h-ful flex w-full flex-1 flex-col gap-4 rounded-lg">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-primary md:w-full">
                    <Heatmap :heat-dates="props.heatDates"/>
                </div>
                <div class="h-75 w-[350px] rounded-xl bg-primary md:w-full">
                    <BarChart
                        :desc="week.title"
                        title="Analise de foco"
                        :label="[
                            'Dom',
                            'Seg',
                            'Ter',
                            'Qua',
                            'Qui',
                            'Sex',
                            'Sáb',
                        ]",
                        :data="props.week.data"
                        :total="props.week.total"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
