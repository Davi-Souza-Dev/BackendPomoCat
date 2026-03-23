<script lang="ts" setup>
import BarChart from '@/components/analytic/BarChart.vue';
import Heatmap from '@/components/analytic/Heatmap.vue';
import LineChart from '@/components/analytic/LineChart.vue';
import Streakbar from '@/components/analytic/Streakbar.vue';
import AppLayout from '@/layout/AppLayout.vue';
</script>

<template>
    <AppLayout>
        <div class="h-ful flex w-full flex-1 flex-col gap-4 rounded-lg">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-primary md:w-full">
                    <Heatmap />
                </div>
                <div class="h-75 w-[350px] rounded-xl bg-primary md:w-full">
                    <BarChart
                        desc="Tempo de Foco total 50 min"
                        title="Distribuição de Foco"
                        :label="[
                            'Dom',
                            'Seg',
                            'Ter',
                            'Qua',
                            'Qui',
                            'Sex',
                            'Sáb',
                        ]"
                    />
                </div>
                <div class="h-75 w-[350px] rounded-xl bg-primary md:w-full">
                    <LineChart />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
