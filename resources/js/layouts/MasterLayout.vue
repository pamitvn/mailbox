<template>
   <TopNavigation />
   <div id='layoutSidenav'>
      <div id='layoutSidenav_nav'>
         <nav class='sidenav shadow-right sidenav-light'>
            <div class='sidenav-menu'>
               <MainMenu :data='mainMenu' />
            </div>
            <!-- Sidenav Footer-->
            <div class='sidenav-footer'>
               <div class='sidenav-footer-content'>
                  <div class='sidenav-footer-subtitle'>Logged in as:</div>
                  <div class='sidenav-footer-title'>{{ userName }}</div>
               </div>
            </div>
         </nav>
      </div>
      <div id='layoutSidenav_content'>
         <transition name='fade' mode='out-in' appear>
            <main :key='$page.url'>
               <slot />
            </main>
         </transition>
         <footer class='footer-admin mt-auto footer-light'>
            <TheFooter />
         </footer>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import { computed } from 'vue';
   import { useAuth, useLayout } from '~/uses';
   import TopNavigation from '~/components/Layouts/Navigation/TopNavigation';
   import MainMenu from '~/components/Layouts/Menu/MainMenu/MainMenu';
   import TheFooter from '~/components/Layouts/Footer/TheFooter';

   const mainMenu = computed(() => useLayout('menu.main'));

   const userName = computed(() => useAuth<string>('user.name', 'Guest'));
</script>

<style>
   .fade-enter-active,
   .fade-leave-active {
      transition: opacity 0.5s ease;
   }

   .fade-enter-from,
   .fade-leave-to {
      opacity: 0;
   }
</style>
