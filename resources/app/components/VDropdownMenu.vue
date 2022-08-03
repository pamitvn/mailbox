<template>
   <v-dropdown align='right' class='relative inline-flex'>
      <template #dropdown>
         <slot />
      </template>

      <span class='sr-only'>Menu</span>
      <svg class='w-8 h-8 fill-current' viewBox='0 0 32 32'>
         <circle cx='16' cy='16' r='2' />
         <circle cx='10' cy='16' r='2' />
         <circle cx='22' cy='16' r='2' />
      </svg>
   </v-dropdown>
</template>

<script setup lang='ts'>
   import VDropdown from '~/components/VDropdown.vue';
</script>
