<template>
   <div v-if='label' class='block text-sm font-medium mb-1'>
      {{ label }}
   </div>
   <editor v-model='input' :api-key='getApiKey' />
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, useAttrs, watch } from 'vue';
   import Editor from '@tinymce/tinymce-vue';
   import { usePage } from '@inertiajs/inertia-vue3';

   const props = defineProps<{
      modelValue?: string | number
      label?: string
   }>();
   const emit = defineEmits(['update:modelValue']);
   const attrs = useAttrs();

   const input = ref(props.modelValue || '');

   const getApiKey = computed(() => _.get(usePage().props.value, 'TinyMCE.api_key'));

   watch(input, (val) => {
      emit('update:modelValue', val);
   });
</script>
