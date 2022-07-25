<template>
   <teleport to='body'>
      <v-modal v-if='notification?.enable'
               title='Notification'
               max-width='lg'
               :has-header='false'
               :has-scroll-bar='true'
               open
      >
         <div class='p-6'>
            <h1 v-if='notification?.title' class='text-3xl mb-2 text-center'>{{ notification?.title }}</h1>
            <article class='prose prose-slate mx-auto lg:prose-lg' v-html='notification?.content'></article>
         </div>
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';

   import VModal from '~/components/VModal.vue';

   const notification = computed(() => _.get(usePage().props.value, 'notification', {
      enable: false,
   }));
</script>
