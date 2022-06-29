<template>
   <nav class='nav nav-borders'>
      <the-link
         v-for='(item, key) in props.data'
         class='nav-link'
         :class='getClass(item, key)'
         :href='getUrl(item)'
      >
         {{ item.label }}
      </the-link>
   </nav>
</template>

<script setup lang='ts'>
   import { usePage } from '@inertiajs/inertia-vue3';
   import { matchedURL, useRoute } from '~/utils';

   const props = defineProps({
      data: {
         type: Array,
         default: [],
      },
   });

   const getUrl = (item) => useRoute('admin.setting', { setting: item.key });
   const active = (item) => {
      try {
         return matchedURL(new URL(usePage().url.value, window.location.origin).toString(), getUrl(item));
      } catch (e) {
         return false;
      }
   };
   const getClass = (item, key) => {
      return {
         [item.class]: true,
         'ms-0': key === 0,
         active: active(item),
      };
   };
</script>
