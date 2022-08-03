<template>
   <div class='flex items-center justify-center'>
      <vue-gravatar v-if='props.hasAvatar && props.gravatar'
                    class='shrink rounded-md'
                    :email='props.gravatar'
                    default-img='mp'
                    :size='35'
                    alt='User Avatar' />
      <div class='ml-2' v-html='getLabel'></div>
   </div>

</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed } from 'vue';

   const props = withDefaults(defineProps<{
      data: Object,
      template?: string,
      gravatar?: string,
      hasAvatar?: boolean
   }>(), {
      template: '<div class="leading-5 text-left"><span class=\'bold\'><b><%= name %></b></span><span class=\'block \'><em><%= username %></em></span></div>',
      gravatar: '',
      hasAvatar: false,
   });

   const getLabel = computed(() => _.template(props.template)(props.data));
</script>
