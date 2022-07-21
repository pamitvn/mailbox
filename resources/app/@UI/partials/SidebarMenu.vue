<template>
   <template v-for='(data, index) in getData' :key='index'>
      <template v-if='data.group && data.items?.length'>
         <div>
            <h3 class='text-xs uppercase text-slate-500 font-semibold pl-3'>
               <span class='hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6'
                     aria-hidden='true'>•••</span>
               <span class='lg:hidden lg:sidebar-expanded:block 2xl:block'>{{ data?.group }}</span>
            </h3>
            <ul class='mt-3'>
               <template v-for='(item,index) in data?.items ?? []' :key='index'>
                  <sidebar-menu-item
                     :label='item.label ?? ""'
                     :class='item.class ?? ""'
                     :icon='item.icon ?? ""'
                     :icon-active='item.iconActive ?? ""'
                     :target='item.target'
                     :extra-matched='item.extraMatched'
                     :items='item?.items ?? []'
                     :auth='item?.auth ?? false'
                  />
               </template>
            </ul>
         </div>
      </template>
   </template>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed } from 'vue';
   import useAuth from '~/uses/useAuth';
   import SidebarMenuItem from '@UI/partials/SidebarMenuItem.vue';

   const props = defineProps({
      data: {
         type: Array,
         default: [],
      },
   });

   const getData = computed(() => _.orderBy(_.map(props.data, i => ({
      ...i,
      order: i.order === undefined ? 10 : i.order,
   })), ['order'], ['asc']));
   const checkAuth = (item) => item.auth ? useAuth<boolean>('isLoggedIn') : true;
</script>
