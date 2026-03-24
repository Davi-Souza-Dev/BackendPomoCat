<script lang="ts" setup>
import Heatmap from '@/components/analytic/Heatmap.vue';
import DistGraph from '@/components/analytic/DistGraph.vue';
import Streakbar from '@/components/analytic/Streakbar.vue';
import AppLayout from '@/layout/AppLayout.vue';
import { useGraphDist } from '@/stores/analytics/GraphDistFocus';

interface Props{
    heatDates: string[],
    week:{
        title: string,
        data: number[],
        total: number
    }
}

const props = defineProps<Props>();
const graphDistStore = useGraphDist();
graphDistStore.week = props.week;

</script>

<template>
    <AppLayout>
        <div class="h-ful flex w-full flex-1 flex-col gap-4 rounded-lg">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="rounded-xl bg-primary md:w-full">
                    <Heatmap :heat-dates="props.heatDates"/>
                </div>
                <div class="h-75 w-[350px] rounded-xl bg-primary md:w-full">
                    <DistGraph/>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
