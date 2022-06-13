<template>
   <!-- Sidenav Menu Heading -->
   <div class='sidenav-menu-heading'
        :class='props.class'
   >
      {{ props.name }}
   </div>
</template>

<script setup>
   const props = defineProps({
      name: String,
      class: {
         type: String,
         required: false,
         default: '',
      },
   });
</script>
