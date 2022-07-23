<template>
   <default-layout>
      <!-- Page header -->
      <div class='mb-8'>
         <!-- Title -->
         <h1 class='text-2xl md:text-3xl text-slate-800 font-bold'>Account Settings ✨</h1>
      </div>

      <!-- Content -->
      <div class='bg-white shadow-lg rounded-sm mb-8'>
         <div class='flex flex-col md:flex-row md:-mr-px'>
            <account-sidebar :menu='menu' />
            <transition name='fade' mode='out-in' appear>
                  <slot />
            </transition>
         </div>
      </div>
   </default-layout>
</template>

<script setup lang='ts'>
   import { computed } from 'vue';
   import { useLayout } from '~/uses';

   import DefaultLayout from './DefaultLayout.vue';
   import AccountSidebar from '~/partials/account/AccountSidebar.vue';

   const menu = computed(() => useLayout('menu.account', []));
</script>
