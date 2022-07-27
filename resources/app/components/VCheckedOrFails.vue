<template>
   <span v-if='value'>
      <font-awesome-icon icon='fa-regular fa-circle-check' />
   </span>
   <span v-else>
      <font-awesome-icon icon='fa-regular fa-circle-xmark' />
   </span>
</template>

<script setup lang='ts'>
   defineProps<{
      value?: boolean
   }>();
</script>
