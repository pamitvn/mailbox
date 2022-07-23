<template>
   <slot />
</template>

<script setup lang='ts'>
   import { onMounted, onUnmounted, watchEffect } from 'vue';

   import { Echo } from '~/utils';
   import { useAuth, useToast } from '~/uses';
   import useAuthStore from '~/stores/useAuthStore';

   import { Models } from '~/types/Models';

   const useStore = useAuthStore();

   onMounted(() => {
      if (!useAuth('isLoggedIn', false)) return;

      const channel = Echo.private(`user.${useAuth('user.id')}`);

      const onMessage = e => {
         try {
            const { type, message } = e;

            if (!type || !message) throw 'Invalid type';

            useToast(message, { type });
         } catch (e) {

         }
      };

      channel.listen('.message', onMessage);

      onUnmounted(() => {
         channel.stopListening('.message', onMessage);
      });
   });

   watchEffect(() => {
      useStore.$patch({
         isLoggedIn: useAuth<boolean>('isLoggedIn', false),
         user: useAuth<Models.User>('user', {}),
      });
   });
</script>
