<script lang="ts" setup>
import type { CalendarRootEmits, CalendarRootProps, DateValue } from "reka-ui"
import type { HTMLAttributes } from "vue"
import { reactiveOmit } from "@vueuse/core"
import { CalendarRoot, useForwardPropsEmits } from "reka-ui"
import { cn } from "@/lib/utils"
import { CalendarCell, CalendarCellTrigger, CalendarGrid, CalendarGridBody, CalendarGridHead, CalendarGridRow, CalendarHeadCell, CalendarHeader, CalendarHeading, CalendarNextButton, CalendarPrevButton } from "."
import { Lock } from "lucide-vue-next"

const props = defineProps<CalendarRootProps & { class?: HTMLAttributes["class"],
  heatDates?: string[]
}>()

const emits = defineEmits<CalendarRootEmits>()

const delegatedProps = reactiveOmit(props, "class")

const forwarded = useForwardPropsEmits(delegatedProps, emits)
const isHeat = (date: DateValue) => {
  return props.heatDates?.includes(date.toString())
}
</script>
<template>
  <CalendarRoot
    v-slot="{ grid, weekDays }"
    :class="cn('p-4 w-full border rounded-xl shadow-lg md:max-w-fit', props.class)"
    v-bind="forwarded"
  >
    <CalendarHeader class="px-2 pb-4 justify-center flex w-full items-center ">
      <!-- <CalendarPrevButton class="md:h-10 md:w-10" />  -->
      <CalendarHeading class="md:text-xl font-bold uppercase" /> 
      <!-- <CalendarNextButton class="md:h-10 md:w-10" /> -->
    </CalendarHeader>

    <div class="flex flex-col gap-y-4 mt-2 sm:flex-row sm:gap-x-4 sm:gap-y-0">
      <CalendarGrid v-for="month in grid" :key="month.value.toString()" class="md:w-full border-separate border-spacing-y-2">
        <CalendarGridHead>
          <CalendarGridRow class="flex justify-between">
            <CalendarHeadCell
              v-for="day in weekDays" :key="day"
              class="md:w-full text-base font-medium text-muted-foreground uppercase"
            >
              {{ day }}
            </CalendarHeadCell>
          </CalendarGridRow>
        </CalendarGridHead>
        
        <CalendarGridBody>
          <CalendarGridRow 
            v-for="(weekDates, index) in month.rows" 
            :key="`weekDate-${index}`" 
            class="flex p-1 md:w-full  gap-1 gap-y-1"
          >
            <CalendarCell
          v-for="weekDate in weekDates"
          :key="weekDate.toString()"
          :date="weekDate">
          <CalendarCellTrigger
            :day="weekDate"
            :month="month.value"
            :class="cn(
              'h-full w-full flex flex-col items-center justify-center gap-1 rounded-md text-lg transition-none relative',
              'focus:bg-transparent  gap-1 p-1', 
              isHeat(weekDate) && 'text-primary bg-foreground'
            )"
          >

           <div class="aspect-square w-full bg-transparent" v-if="isHeat(weekDate)">
                <img src="/images/heat.png">
            </div>

            <span v-if="!isHeat(weekDate)">
              {{ weekDate.day }}
            </span>

           
            
          </CalendarCellTrigger>
        </CalendarCell>
          </CalendarGridRow>
        </CalendarGridBody>
      </CalendarGrid>
    </div>
  </CalendarRoot>
</template>
