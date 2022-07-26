<template>
   <div
      class='flex flex-nowrap overflow-x-scroll no-scrollbar md:block md:overflow-auto px-3 py-6 border-b md:border-b-0 md:border-r border-slate-200 min-w-60 md:space-y-3'>
      <div>
         <div class='text-xs font-semibold text-slate-400 uppercase mb-3'>Menu</div>
         <ul class='flex flex-nowrap md:block mr-3 md:mr-0'>

            <li v-for='(item, index) in menu' :key='index' class='mr-0.5 md:mr-0 md:mb-0.5'>
               <the-link
                  class='wrapper'
                  :class='getClass(item, index)'
                  :href='getUrl(item)'
               >
                  <span v-if='item.icon' class='icon' v-html='item.icon'></span>
                  <span class='label'>{{ item.label }}</span>
               </the-link>
            </li>
         </ul>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import { usePage } from '@inertiajs/inertia-vue3';
   import { matchedURL, useRoute } from '~/utils';

   interface MenuItem {
      label: string;
      target: string;
      class?: string;
      icon?: string;
   }

   const props = defineProps<{
      menu: MenuItem[]
   }>();

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
         [!!item.class ? item.class : '']: true,
         active: active(item),
      };
   };
</script>

<style lang='scss' scoped>
   .wrapper {
      @apply flex items-center px-2.5 py-2 rounded whitespace-nowrap cursor-pointer;

      .icon {
         @apply w-4 h-4 shrink-0 fill-current text-slate-500 mr-2;
      }

      .label {
         @apply text-sm font-medium text-slate-600 hover:text-slate-700;
      }

      #{if(&, '&.active', '.active')} {
         @apply bg-indigo-50;

         .icon {
            @apply text-indigo-400;
         }

         .label {
            @apply text-indigo-500;
         }
      }
   }

</style>
