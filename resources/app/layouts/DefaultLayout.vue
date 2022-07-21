<template>
   <div class='flex h-screen overflow-hidden'>
      <!-- Sidebar -->
      <the-sidebar />

      <!-- Content area -->
      <div class='relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden'>
         <the-header />

         <main>
            <div class='px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto'>
               <slot />
            </div>
         </main>
      </div>
   </div>
</template>
<script setup lang='ts'>
   import TheSidebar from '@UI/partials/Sidebar.vue';
   import TheHeader from '@UI/partials/Header.vue';
</script>
