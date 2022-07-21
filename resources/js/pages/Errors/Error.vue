<template>
   <keep-alive>
      <component :is='component'></component>
   </keep-alive>
</template>

<script lang='ts'>
   import { computed, defineComponent } from 'vue';
   import ErrorLayout from '~/layouts/ErrorLayout.vue';
   import Error403 from './Error403.vue';
   import Error404 from './Error404.vue';
   import Error500 from './Error500.vue';
   import Error503 from './Error503.vue';

   export default defineComponent({
      name: 'Error',
      layout: ErrorLayout,
      props: {
         status: {
            type: [Number, String],
            default: 500,
         },
      },
      setup(props) {
         const component = computed(() => {
            return {
               403: Error403,
               404: Error404,
               500: Error500,
               503: Error503,
            }[props.status] ?? 'Unknown';
         });

         return { component };
      },
   });
</script>
