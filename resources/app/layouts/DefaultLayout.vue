<template>
   <div class='flex h-screen overflow-hidden'>
      <!-- Sidebar -->
      <the-sidebar />

      <!-- Content area -->
      <div class='relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden'>
         <the-header />


         <div class='px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto'>
            <transition v-if='props.withTransition' name='fade' mode='out-in' appear
            >
               <main :key='$page.url'>
                  <slot />
               </main>
            </transition>
            <main v-else>
               <slot />
            </main>
         </div>

         <!--         <div class='flex justify-center items-center space-x-1 font-serif text-lg antialiased capitalize align-middle'>-->
         <!--            <span>Powered by</span>-->
         <!--            <a href='https://phamminhan.ceo' class='underline decoration-pink-500 hover:text-red-600' target='_blank'>PAM</a>-->
         <!--         </div>-->
      </div>
   </div>
</template>
<script setup lang='ts'>
   import TheSidebar from '@UI/partials/Sidebar.vue';
   import TheHeader from '@UI/partials/Header.vue';

   const props = withDefaults(defineProps<{
      withTransition?: boolean
   }>(), {
      withTransition: true,
   });


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
