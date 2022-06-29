<template>
   <the-link class='navbar-brand pe-3 ps-4 ps-lg-2' href='/'>
      {{ siteName }}
   </the-link>
</template>

<script setup>
   import { computed } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';
   import _ from 'lodash';

   const siteName = computed(() => _.get(usePage().props.value, 'site.name'));
</script>
