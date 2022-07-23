<template>
   <div class='relative inline-flex'>
      <button
         ref='trigger'
         class='inline-flex justify-center items-center group'
         aria-haspopup='true'
         @click.prevent='dropdownOpen = !dropdownOpen'
         :aria-expanded='dropdownOpen'
      >
         <div class='flex items-center truncate md:flex'>
            <span class='btn bg-emerald-500 hover:bg-emerald-600 text-white rounded-full p-2 sm:px-4 sm:rounded-lg'>
              <font-awesome-icon icon='fa-regular fa-circle-user' class='w-6 h-6' />
               <span class='ml-2 hidden sm:block'>{{ user?.name ?? 'Account' }}</span>
            </span>
         </div>
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
              ref='wrapper'
              class='origin-top-right z-10 absolute top-full min-w-44 bg-white border border-slate-200 py-1.5 rounded shadow-lg overflow-hidden mt-1'
              :class="align === 'right' ? 'right-0' : 'left-0'">
            <div v-if='isLoggedIn' class='pt-0.5 pb-2 px-3 mb-1 border-b border-slate-200'>
               <div class='font-medium text-slate-800'>{{ user?.name }}</div>
               <div class='text-xs text-slate-500 italic'>{{ user?.is_admin ? 'Admin' : 'User' }}</div>
            </div>
            <ul
               ref='dropdown'
               @focusin='dropdownOpen = true'
               @focusout='dropdownOpen = false'
            >
               <template v-if='isLoggedIn'>
                  <li>
                     <the-link
                        class='font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3'
                        :href='$route("account.profile")' @click='dropdownOpen = false'
                     >
                        Account
                     </the-link>
                  </li>
                  <li>
                     <the-link
                        class='font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3'
                        as='button'
                        :href='$route("logout")'
                        method='POST'
                        @click='dropdownOpen = false'
                     >
                        Sign Out
                     </the-link>
                  </li>
               </template>
               <template v-else>
                  <li>
                     <the-link
                        class='font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3'
                        :href='$route("login")' @click='dropdownOpen = false'>
                        Login
                     </the-link>
                  </li>
                  <li>
                     <the-link
                        class='font-medium text-sm text-indigo-500 hover:text-indigo-600 flex items-center py-1 px-3'
                        :href='$route("login")'
                        @click='dropdownOpen = false'>
                        Register
                     </the-link>
                  </li>
               </template>
            </ul>
         </div>
      </transition>
   </div>
</template>

<script setup lang='ts'>
   import { onMounted, onUnmounted, ref } from 'vue';
   import useAuthStore from '~/stores/useAuthStore';
   import { storeToRefs } from 'pinia/dist/pinia';

   const props = defineProps<{
      align: string
   }>();

   const authStore = useAuthStore();

   const { isLoggedIn, user } = storeToRefs(authStore);

   const dropdownOpen = ref(false);
   const wrapper = ref(null);
   const trigger = ref(null);
   const dropdown = ref(null);

   // close on click outside
   const clickHandler = ({ target }) => {
      if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target) || wrapper.value?.contains(target)) return;

      dropdownOpen.value = false;
   };

   // close if the esc key is pressed
   const keyHandler = ({ keyCode }) => {
      if (!dropdownOpen.value || keyCode !== 27) return;
      dropdownOpen.value = false;
   };

   onMounted(() => {
      document.addEventListener('click', clickHandler);
      document.addEventListener('keydown', keyHandler);
   });

   onUnmounted(() => {
      document.removeEventListener('click', clickHandler);
      document.removeEventListener('keydown', keyHandler);
   });

</script>
