<script setup lang="ts">
import { AlertDialog, AlertDialogContent } from '@/components/ui/alert-dialog'

interface Props {
  isLoading: boolean
}

defineProps<Props>()
</script>
<template>
  <AlertDialog :open="isLoading">
    <AlertDialogContent class="border-none bg-transparent shadow-none flex flex-col items-center justify-center outline-none">
      
      <div class="relative flex items-center justify-center">
        <div class="absolute inset-0 bg-primary/20 blur-3xl rounded-full animate-pulse"></div>
        
        <div class="animate-spin-slow">
          <img 
            src="/images/carregando.png" 
            alt="Carregando..." 
            class="w-32 h-32 md:w-48 md:h-48 object-contain"
          />
        </div>
      </div>

      <p class="mt-6 text-white font-bold text-2xl md:text-3xl animate-bounce tracking-widest uppercase italic">
        Miaugando...
      </p>

    </AlertDialogContent>
  </AlertDialog>
</template>


<style scoped>
/* Rotação mais suave que a do Tailwind padrão */
.animate-spin-slow {
  animation: spin 3s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Garante que o fundo do modal seja bem escuro para focar no gato */
:deep(.fixed.inset-0) {
  background-color: rgba(0, 0, 0, 0.85) !important;
  backdrop-filter: blur(4px);
}
</style>