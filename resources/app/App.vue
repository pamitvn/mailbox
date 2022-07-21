<template>
   <slot />
</template>

<script setup lang='ts'>
   // import _ from 'lodash';
   // import { computed, onMounted, onUnmounted, ref, watch, watchEffect } from 'vue';
   // import { usePage } from '@inertiajs/inertia-vue3';
   // import { useAuth, useToast } from '~/uses';
   // import { Echo, reactiveToJson } from '~/utils';
   //
   // const messageChannel = ref(null);
   //
   // const flashMessage = computed(() => _.get(reactiveToJson(usePage().props.value ?? {}), 'flash', {}));
   //
   // onMounted(() => {
   //    if (!useAuth('isLoggedIn', false)) return;
   //
   //    messageChannel.value = `user.${useAuth('user.id')}`;
   //
   //    Echo.private(messageChannel.value)
   //       .listen('.message', e => {
   //          try {
   //             const { type, message } = e;
   //             useToast(message, { type });
   //          } catch (e) {
   //
   //          }
   //       });
   // });
   //
   // onUnmounted(() => {
   //    Echo.leave(messageChannel.value);
   // });
   //
   // watch(() => flashMessage.value, () => {
   //    if (!flashMessage.value) return;
   //
   //    Object.keys(_.pickBy(flashMessage.value, _.identity))
   //       .forEach((flash) => {
   //          let type = 'default';
   //
   //          switch (flash) {
   //             case 'success':
   //                type = 'success';
   //                break;
   //             case 'error':
   //                type = 'danger';
   //                break;
   //             case 'status':
   //                type = 'info';
   //                break;
   //          }
   //
   //          useToast(flashMessage.value[flash], {
   //             type,
   //          });
   //       });
   // });

   import { provide } from 'vue';
   import { useAuth } from '~/uses';
   import { Models } from '~/types/Models';

   provide('user', useAuth<Models.User>('user', {}));
   provide('isLoggedIn', useAuth<boolean>('isLoggedIn', false));
</script>
