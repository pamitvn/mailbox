<template>
   <slot />
</template>

<script setup>
   import _ from 'lodash';
   import { computed, onMounted, onUnmounted, ref, watchEffect } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';
   import { useAuth, useToast } from '~/uses';
   import { reactiveToJson } from '~/utils';

   const messageChannel = ref(null);

   const flashMessage = computed(() => _.get(reactiveToJson(usePage().props.value ?? {}), 'flash', {}));

   watchEffect(() => {
      if (!flashMessage.value) return;

      Object.keys(_.pickBy(flashMessage.value, _.identity))
         .forEach((flash) => {
            let type = 'default';

            switch (flash) {
               case 'success':
                  type = 'success';
                  break;
               case 'error':
                  type = 'danger';
                  break;
               case 'status':
                  type = 'info';
                  break;
            }

            useToast(flashMessage.value[flash], {
               type,
            });
         });
   });

   onMounted(() => {
      if (!useAuth('isLoggedIn', false)) return;

      messageChannel.value = `user.${useAuth('user.id')}`;

      window.Echo.private(messageChannel.value)
         .listen('.message', e => {
            try {
               const { type, message } = e;
               useToast(message, { type });
            } catch (e) {

            }
         });
   });

   onUnmounted(() => {
      window.Echo.leave(messageChannel.value);
   });
</script>
