<template>
   <v-input type='input' v-bind='attrs' v-slot='{classname}'>
      <textarea v-model='input'
                :class='classname'
                :rows='rows'
                :cols='cols'
                :maxlength='maxLength'
                :readonly='readonly'
                class='form-textarea'
      ></textarea>
   </v-input>
</template>

<script setup lang='ts'>
   import { ref, useAttrs, watch } from 'vue';
   import VInput from '~/components/Form/VInput.vue';

   const props = defineProps<{
      modelValue?: string | number
      rows?: string | number
      cols?: string | number
      maxLength?: string | number
      readonly?: boolean
   }>();
   const emit = defineEmits(['update:modelValue']);
   const attrs = useAttrs();

   const input = ref(props.modelValue || '');

   watch(input, (val) => {
      emit('update:modelValue', val);
   });
</script>
