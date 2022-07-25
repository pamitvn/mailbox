<template>
   <v-input v-bind='attrs' type='text' v-slot='{attrs: inputAttrs, event}'>
      <select v-model='selected' v-bind='inputAttrs' class='form-select w-full'>
         <option :value='null'>{{ attrs.label }}</option>
         <option v-for='(item, i) in getOptions' :key='i' :value='i'>{{ item }}</option>
      </select>
   </v-input>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, useAttrs, watch } from 'vue';

   import VInput from '~/components/Form/VInput.vue';

   const props = defineProps<{
      modelValue?: string | number | null
      pathToKey?: string
      pathToLabel?: string
      options: object | object[]
   }>();
   const emit = defineEmits(['update:modelValue']);
   const attrs = useAttrs();

   const selected = ref(props.modelValue || null);

   const getOptions = computed(() => {
      let options = _.clone(props.options);

      if (_.isArray(props.options)) {
         _.forEach(options, item => {
            const key = _.get(item, props.pathToKey);
            const label = _.get(item, props.pathToLabel);

            if (key === undefined) return;

            _.set(options, key, label);
         });
      }

      return options;
   });

   watch(selected, (val) => {
      emit('update:modelValue', val);
   });

</script>
