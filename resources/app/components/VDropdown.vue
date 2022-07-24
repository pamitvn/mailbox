<template>
   <div class='relative inline-flex'>
      <button
         ref='trigger'
         class='btn bg-white border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-600'
         aria-haspopup='true'
         @click.prevent='dropdownOpen = true'
         :aria-expanded='dropdownOpen'
      >
         <slot></slot>
      </button>
      <transition
         enter-active-class='transition ease-out duration-200 transform'
         enter-from-class='opacity-0 -translate-y-2'
         enter-to-class='opacity-100 translate-y-0'
         leave-active-class='transition ease-out duration-200'
         leave-from-class='opacity-100'
         leave-to-class='opacity-0'
      >
         <div v-show='dropdownOpen'
              :key='$page.url'
              class='origin-top-right z-10 absolute top-full min-w-56 bg-white border border-slate-200 pt-1.5 rounded shadow-lg overflow-hidden mt-1 right-0'
              :class="align === 'right' ? 'right-0' : 'left-0'"
         >
            <div ref='dropdown'>
               <slot name='dropdown' v-bind:close='close'></slot>
            </div>
         </div>
      </transition>
   </div>
</template>

<script setup lang='ts'>
   import { ref, onMounted, onUnmounted } from 'vue';

   const props = withDefaults(defineProps<{
      align?: 'right' | 'left'
   }>(), {
      align: 'right',
   });

   const dropdownOpen = ref(false);
   const trigger = ref(null);
   const dropdown = ref(null);

   // close on click outside
   const clickHandler = ({ target }) => {
      if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target)) return;
      close();
   };

   // close if the esc key is pressed
   const keyHandler = ({ keyCode }) => {
      if (!dropdownOpen.value || keyCode !== 27) return;
      close();
   };
   const close = () => dropdownOpen.value = false;

   onMounted(() => {
      document.addEventListener('click', clickHandler);
      document.addEventListener('keydown', keyHandler);
   });

   onUnmounted(() => {
      document.removeEventListener('click', clickHandler);
      document.removeEventListener('keydown', keyHandler);
   });
</script>
