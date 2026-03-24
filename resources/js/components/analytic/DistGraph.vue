<script setup lang="ts">
import { computed, onMounted, onUpdated, ref, watch } from 'vue';
import Chart from 'chart.js/auto';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import Button from '../ui/button/Button.vue';
import { ArrowLeft, ArrowRight } from 'lucide-vue-next';
import { useGraphDist } from '@/stores/analytics/GraphDistFocus';
const focusChartCanvas = ref<HTMLCanvasElement | null>(null);
const distGraphStore = useGraphDist();
const weeklyFocusData = computed(() => ({
    labels: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
    datasets: [
        {
            label: 'Minutos de Foco',
            // O .value aqui é essencial se a store for reativa
            data: distGraphStore.week.data,
            backgroundColor: 'rgba(249, 115, 22, 0.2)',
            borderColor: 'rgb(249, 115, 22)',
            borderWidth: 2,
            borderRadius: 4,
            borderSkipped: false,
        },
    ],
}));

let chartInstance: any = null;
const initeChart = () =>{
        if (focusChartCanvas.value) {
        chartInstance = new Chart(focusChartCanvas.value, {
            type: 'bar',
            data: weeklyFocusData.value,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: (context) => ` ${context.raw}`,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(156, 163, 175, 0.1)',
                        },
                        ticks: {
                            callback: (value) => `${value}`,
                        },
                    },
                    x: {
                        grid: {
                            display: false,
                        },
                    },
                },
            },
        });
    }
}

onMounted(() => {
    initeChart();
});

watch(() => distGraphStore.week.data, (newData) => {
    if (chartInstance) {
        chartInstance.data = weeklyFocusData.value;
        chartInstance.update();
    }
}, { deep: true });
</script>

<template>
    <Card class="h-full w-full">
        <CardHeader>
            <CardTitle>Distribuição de foco</CardTitle>
            <CardDescription class="flex items-center justify-center">
                <Button variant="ghost" @click="distGraphStore.prevWeek"
                    ><ArrowLeft
                /></Button>
                {{ distGraphStore.week.title }}
                <Button variant="ghost" v-if="distGraphStore.offset > 0" @click="distGraphStore.nextweek"
                    ><ArrowRight
                /></Button>
            </CardDescription>
            <CardDescription>
                Tempo total de foco:
                <strong class="text-foreground">{{
                    distGraphStore.week.total
                }}</strong>
                min
            </CardDescription>
        </CardHeader>
        <CardContent>
            <div>
                <canvas ref="focusChartCanvas"></canvas>
            </div>
        </CardContent>
    </Card>
</template>
