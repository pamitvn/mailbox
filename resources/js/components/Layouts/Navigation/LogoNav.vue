<template>
   <the-link class='navbar-brand pe-3 ps-4 ps-lg-2' style='width: 20rem' href='/'>
      <template v-if='hasLogoImage()'>
         <img :src='Logo()' :alt='siteName' class='w-100 h-100'>
      </template>
      <template v-else>
         {{ siteName }}
      </template>
   </the-link>
</template>

<script setup>
   import _ from 'lodash';
   import { computed } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';

   const siteName = computed(() => _.get(usePage().props.value, 'site.name'));

   const hasLogoImage = () => {
      try {
         require('./../../../../dist/images/logo.png').default;
         return true;
      } catch (e) {
         console.log(e);
         return false;
      }
   };
   const Logo = () => require('./../../../../dist/images/logo.png').default;
</script>
