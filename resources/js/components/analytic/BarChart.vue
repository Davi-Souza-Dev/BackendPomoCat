<script setup lang="ts">
import { onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
interface Props{
  label: string[],
  title: string,
  desc: string,

}

const props = defineProps<Props>();
const focusChartCanvas = ref<HTMLCanvasElement | null>(null)

const weeklyFocusData = {
  labels: props.label,
  datasets: [{
    label: 'Minutos de Foco',
    data: [45, 120, 75, 200, 150, 60, 32], //MINUTOS
    backgroundColor: 'rgba(249, 115, 22, 0.2)',
    borderColor: 'rgb(249, 115, 22)',           
    borderWidth: 2,
    borderRadius: 4,
    borderSkipped: false,
  }]
}

onMounted(() => {
  if (focusChartCanvas.value) {
    new Chart(focusChartCanvas.value, {
      type: 'bar',
      data: weeklyFocusData,
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
          tooltip: {
            callbacks: {
              label: (context) => ` ${context.raw}`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              display: true,
              color: 'rgba(156, 163, 175, 0.1)',
            },
            ticks: {
              callback: (value) => `${value}`
            }
          },
          x: {
            grid: {
              display: false 
            }
          }
        }
      }
    })
  }
})
</script>

<template>
  <Card class="h-full w-full">
    <CardHeader>
      <CardTitle>{{ props.title }}</CardTitle>
      <CardDescription>{{ props.desc }}</CardDescription>
    </CardHeader>
    <CardContent>
      <div>
        <canvas ref="focusChartCanvas"></canvas>
      </div>
    </CardContent>
  </Card>
</template>