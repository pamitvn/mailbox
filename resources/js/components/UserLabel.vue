<template>
   <div class='d-flex g-2 align-items-center'>
      <vue-gravatar v-if='props.hasAvatar && props.gravatar'
                    class='flex-shrink-0'
                    :email='props.gravatar'
                    default-img='mp'
                    :size='35'
                    alt='User Avatar' />
      <div class='flex-grow-1 ms-2' v-html='getLabel'></div>
   </div>

</template>

<script setup lang='ts'>
   import { computed } from 'vue';
   import _ from 'lodash';

   const props = withDefaults(defineProps<{
      data: Object,
      template?: string,
      gravatar?: string,
      hasAvatar?: boolean
   }>(), {
      template: '<span class=\'bold\'><b><%= name %></b></span><span class=\'d-block\'><em><%= username %></em></span>',
      gravatar: '',
      hasAvatar: false,
   });

   const getLabel = computed(() => _.template(props.template)(props.data));
</script>
