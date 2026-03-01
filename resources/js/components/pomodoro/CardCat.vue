<script setup lang="ts">
interface Props {
  index: number
  title: string
  image: string
  rarity: string
}

const props = defineProps<Props>()
</script>

<template>
  <div
    class="w-27 h-35 bg-background-app rounded flex flex-col gap-1 justify-around align-middle items-center"
  >
    <span class="text-l font-pixel uppercase"> #{{ String(index).padStart(3, '0') }} </span>
    <img :src="image" class="w-20" :class="`rarity-${rarity}`" />
    <span class="text-l font-pixel uppercase text-center"> {{ title }}</span>
  </div>
</template>

<style lang="css" scoped>
.rarity-image {
  transition: all 0.3s ease-in-out;
  padding: 10px
}

/* Comum */
.rarity-comum {
  filter: drop-shadow(0 0 8px #9ca3af);
}

/* Raro */
.rarity-raro {
  filter: drop-shadow(0 0 12px #3b82f6);
}

/* Épico */
.rarity-epíco {
  filter: drop-shadow(0 0 15px #a855f7);
  animation: pulse-rarity 2s infinite;
}

/* Lendário */
.rarity-lendário {
  filter: drop-shadow(0 0 20px #f59e0b) drop-shadow(0 0 35px #fbbf24);
  animation: shine-gold 3s infinite;
}


@keyframes pulse-rarity {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; filter: drop-shadow(0 0 25px rgba(168, 85, 247, 0.9)); }
}

@keyframes shine-gold {
  0% { filter: drop-shadow(0 0 15px #f59e0b); }
  50% { filter: drop-shadow(0 0 30px #fbbf24); transform: scale(1.02); }
  100% { filter: drop-shadow(0 0 15px #f59e0b); }
}
</style>
