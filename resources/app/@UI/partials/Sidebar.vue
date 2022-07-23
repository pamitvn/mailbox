<template>
   <div>
      <!-- Sidebar backdrop (mobile only) -->
      <div class='fixed inset-0 bg-slate-900 bg-opacity-30 z-40 lg:hidden lg:z-auto transition-opacity duration-200'
           :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
           aria-hidden='true'
           @click.stop='() => hideSidebar()'></div>

      <!-- Sidebar -->
      <div
         id='sidebar'
         ref='sidebar'
         class='flex flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-screen overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:!w-64 shrink-0 bg-slate-800 p-4 transition-all duration-200 ease-in-out'
         :class="sidebarOpen ? 'translate-x-0' : '-translate-x-64'"
      >

         <!-- Sidebar header -->
         <div class='flex justify-between mb-10 pr-3 sm:px-2'>
            <!-- Close button -->
            <button
               ref='trigger'
               class='lg:hidden text-slate-500 hover:text-slate-400'
               @click.stop='() => hideSidebar()'
               aria-controls='sidebar'
               :aria-expanded='sidebarOpen'
            >
               <span class='sr-only'>Close sidebar</span>
               <svg class='w-6 h-6 fill-current' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                  <path d='M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z' />
               </svg>
            </button>
            <!-- Logo -->
            <!--            <the-link class='block' href='/'>-->
            <!--               <img class='ml-2 md:ml-4 lg:ml-0 md:w-60 h-full' :src='Logo' alt='MailBox'>-->
            <!--            </the-link>-->
         </div>

         <!-- Links -->
         <div class='space-y-8'>
            <sidebar-menu :data='mainMenu' />
         </div>

         <!-- Expand / collapse button -->
         <div class='pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto'>
            <div class='px-3 py-2'>
               <button @click.prevent='() => toggleOpenSidebar()'>
                  <span class='sr-only'>Expand / collapse sidebar</span>
                  <svg class='w-6 h-6 fill-current sidebar-expanded:rotate-180' viewBox='0 0 24 24'>
                     <path class='text-slate-400'
                           d='M19.586 11l-5-5L16 4.586 23.414 12 16 19.414 14.586 18l5-5H7v-2z' />
                     <path class='text-slate-600' d='M3 23H1V1h2z' />
                  </svg>
               </button>
            </div>
         </div>

      </div>
   </div>
</template>

<script setup lang='ts'>
   import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
   import SidebarMenu from './SidebarMenu.vue';
   import { useLayout } from '~/uses';
   import { useThemeStore } from '~/stores/useThemeStore';
   import { storeToRefs } from 'pinia';

   const useStore = useThemeStore();

   const emit = defineEmits(['close-sidebar']);

   const trigger = ref(null);
   const sidebar = ref(null);

   const { sidebarExpanded, sidebarOpen } = storeToRefs(useStore);

   const mainMenu = computed(() => useLayout('menu.main', []));

   // close on click outside
   const clickHandler = ({ target }) => {
      if (!sidebar.value || !trigger.value) return;
      if (
         !sidebarOpen.value ||
         sidebar.value.contains(target) ||
         trigger.value.contains(target)
      ) return;
      emit('close-sidebar');
   };

   // close if the esc key is pressed
   const keyHandler = ({ keyCode }) => {
      if (!sidebarOpen.value || keyCode !== 27) return;
      emit('close-sidebar');
   };

   const toggleOpenSidebar = () => useStore.$patch({ sidebarExpanded: !sidebarExpanded.value });
   const hideSidebar = () => useStore.$patch({ sidebarOpen: false });

   onMounted(() => {
      document.addEventListener('click', clickHandler);
      document.addEventListener('keydown', keyHandler);
   });

   onUnmounted(() => {
      document.removeEventListener('click', clickHandler);
      document.removeEventListener('keydown', keyHandler);
   });

   watch(sidebarExpanded, () => {
      localStorage.setItem('sidebar-expanded', sidebarExpanded.value as string);

      sidebarExpanded.value
         ? document.querySelector('body').classList.add('sidebar-expanded')
         : document.querySelector('body').classList.remove('sidebar-expanded');
   });
</script>
