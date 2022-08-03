<template>
   <default-layout :with-transition='false'>
      <!-- Page header -->
      <div class='mb-8'>
         <!-- Title -->
         <h1 class='text-2xl md:text-3xl text-slate-800 font-bold'>System Settings ✨</h1>
      </div>

      <!-- Content -->
      <div class='bg-white shadow-lg rounded-sm mb-8'>
         <div class='flex flex-col md:flex-row md:-mr-px'>
            <setting-sidebar :menu='menu' />
            <transition name='fade' mode='out-in' appear>
               <main class='grow' :key='$page.url'>
                  <slot />
               </main>
            </transition>
         </div>
      </div>
   </default-layout>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';

   import DefaultLayout from './DefaultLayout.vue';
   import SettingSidebar from '~/partials/setting/SettingSidebar.vue';

   const menu = computed(() => _.cloneDeep(_.get(usePage().props.value, 'groups', [])));
</script>
