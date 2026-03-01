<script setup lang="ts">
import { Calendar, ChartLineIcon, Home, Inbox, Search, Settings } from 'lucide-vue-next'
import {
  Sidebar,
  SidebarContent,
  SidebarGroup,
  SidebarGroupContent,
  SidebarGroupLabel,
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
} from '@/components/ui/sidebar'
import NavUser from './NavUser.vue'
import SidebarFooter from './ui/sidebar/SidebarFooter.vue'
import { useUserStore } from '@/stores/UserStore'

// Menu items.
const items = [
  {
    title: 'Inicio',
    url: '#',
    icon: Home,
  },
  {
    title: 'Dados',
    url: '#',
    icon: ChartLineIcon,
  },
]

const userStore = useUserStore()
</script>

<template>
  <Sidebar>
    <SidebarContent class="bg-background">
      <SidebarGroup>
        <SidebarGroupLabel>Menu</SidebarGroupLabel>
        <SidebarGroupContent>
          <SidebarMenu>
            <SidebarMenuItem v-for="item in items" :key="item.title">
              <SidebarMenuButton as-child>
                <a :href="item.url">
                  <component :is="item.icon" />
                  <span>{{ item.title }}</span>
                </a>
              </SidebarMenuButton>
            </SidebarMenuItem>
          </SidebarMenu>
        </SidebarGroupContent>
      </SidebarGroup>
    </SidebarContent>
    <SidebarFooter class="bg-background">
      <NavUser/>
    </SidebarFooter>
  </Sidebar>
</template>
