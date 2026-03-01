<script setup lang="ts">
import { BellIcon, ChevronsUpDown, LogOut } from 'lucide-vue-next'

import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu'
import {
  SidebarMenu,
  SidebarMenuButton,
  SidebarMenuItem,
  useSidebar,
} from '@/components/ui/sidebar'
import { useUserStore } from '@/stores/UserStore'


const userStore = useUserStore();
</script>

<template>
  <SidebarMenu>
    <SidebarMenuItem>
      <DropdownMenu>
        <DropdownMenuTrigger as-child>
          <SidebarMenuButton
            size="lg"
            class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
          >
            <!-- <Avatar class="h-8 w-8 rounded-lg">
              <AvatarImage :src="userStore.user.photo" />
              <AvatarFallback class="rounded-lg">
                CN
              </AvatarFallback>
            </Avatar> -->
            <div class="grid flex-1 text-left text-sm leading-tight">
              <span class="truncate font-medium">{{ userStore.user.username }}</span>
            </div>
            <ChevronsUpDown class="ml-auto size-4" />
          </SidebarMenuButton>
        </DropdownMenuTrigger>
        <DropdownMenuContent
          class="w-[--reka-dropdown-menu-trigger-width] min-w-56 rounded-lg bg-background"
          side="bottom"
          align="end"
          :side-offset="4"
        >
          <a href="/auth/login">
            <DropdownMenuItem>
              <LogOut />
              Sair
            </DropdownMenuItem>
          </a>
        </DropdownMenuContent>
      </DropdownMenu>
    </SidebarMenuItem>
  </SidebarMenu>
</template>
