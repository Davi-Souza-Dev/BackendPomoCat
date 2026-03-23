<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import Chart from 'chart.js/auto'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"

const chartCanvas = ref<HTMLCanvasElement | null>(null)

const labels = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']
const focusData = [50, 120, 80, 190, 140, 210, 95] 

const maxMinutes = Math.max(...focusData)
const peakDay = labels[focusData.indexOf(maxMinutes)]

onMounted(() => {
  if (chartCanvas.value) {
    new Chart(chartCanvas.value, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Minutos de Foco',
          data: focusData,
          borderWidth: 2,
          backgroundColor: (context) => {
            const value = context.raw as number
            return value === maxMinutes 
              ? 'rgba(249, 115, 22, 1)'   
              : 'rgba(249, 115, 22, 0.2)'
          },
          borderColor: 'rgb(249, 115, 22)',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: (item) => ` ${item.raw} min`
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
          },
          x: { grid: { display: false } }
        }
      }
    })
  }
})
</script>

<template>
  <Card class="overflow-hidden h-full">
    <CardHeader class="pb-2">
      <div class="flex items-center justify-between">
        <div>
          <CardTitle class="text-lg">Pico de Foco</CardTitle>
          <CardDescription>Distribuição da semana</CardDescription>
        </div>
      </div>
    </CardHeader>
    <CardContent>
      <div>
        <canvas ref="chartCanvas"></canvas>
      </div>
    </CardContent>
  </Card>
</template>