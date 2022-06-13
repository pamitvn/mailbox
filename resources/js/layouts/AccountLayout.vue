<template>
   <header class='page-header page-header-compact page-header-light border-bottom bg-white mb-4'>
      <div class='container-xl px-4'>
         <div class='page-header-content'>
            <div class='row align-items-center justify-content-between pt-3'>
               <div class='col-auto mb-3'>
                  <h1 class='page-header-title'>
                     <div class='page-header-icon'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-user'>
                           <path d='M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2'></path>
                           <circle cx='12' cy='7' r='4'></circle>
                        </svg>
                     </div>
                     Account Settings
                  </h1>
               </div>
            </div>
         </div>
      </div>
   </header>
   <!-- Main page content-->
   <div class='container-xl px-4 mt-4'>
      <!-- Account page navigation-->
      <AccountMenu :data='accountMenu' />
      <hr class='mt-0 mb-4'>
      <slot />
   </div>
</template>

<script setup>
   import { computed } from 'vue';

   import AccountMenu from '~/components/Layouts/Menu/AccountMenu/AccountMenu';
   import { useLayout } from '~/uses';

   const accountMenu = computed(() => useLayout('menu.account'));
</script>
