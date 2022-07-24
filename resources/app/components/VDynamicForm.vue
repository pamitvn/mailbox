<template>
   <form @submit.prevent='() => onSubmitForm()'>
      <slot
         v-for='(field, key) in props.modelValue?.fields'
         :key='key'
         :name='key'
         :attrs='getAttrs(field, key)'
         :events='getEvent(field, key)'
      >
         <div class='mt-4 first:mt-0'>
            <component
               :is='getComponent(field)'
               v-model='values[key]'
               v-bind='getAttrs(field, key)'
               @keydown.stop='getEvent(field, key).keydown'
            ></component>
         </div>
      </slot>
   </form>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { onBeforeMount, reactive, watch, watchEffect } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';

   import type Form from '~/types/Components/Form';

   import VInput from '~/components/Form/VInput.vue';
   import DynamicForm = Form.DynamicForm;

   const props = defineProps<{
      modelValue: {
         fields: DynamicForm.Fields
         defaultValues: DynamicForm.DefaultValues
         onSubmit: boolean
      }
   }>();
   const emit = defineEmits(['update:modelValue', 'submit']);

   const values = reactive({});
   const form = useForm({});

   const getAttrs = (field: DynamicForm.Field, key: string) => {
      return {
         ...field.attrs,
         error: form.errors[key],
      };
   };
   const getEvent = (field, key) => {
      return {
         input: (e: InputEvent) => {
            const input: HTMLInputElement = e.target as HTMLInputElement;

            values[key] = input.value;
         },
         keydown: (e: KeyboardEvent) => {
            if (e.code !== 'Enter' && e.code !== 'NumpadEnter') return;

            onSubmitForm();
         },
      };
   };
   const getComponent = (field: DynamicForm.Field) => {
      return _.get(field, 'is', VInput);
   };
   const handleSetValueFn = key => {
      const allowChange = _.get(props.modelValue.fields, `${key}.allowChange`, false);
      const defaultValue = _.get(values, key as string);

      if (defaultValue !== undefined && !allowChange) return;

      values[key] = _.get(props.modelValue.defaultValues, key, defaultValue);
   };
   const initDefaultValue = () => {
      const fields = props.modelValue?.fields;

      if (!_.keys(props.modelValue.fields).length) return;

      _.forEach(_.keys(fields), handleSetValueFn);
   };
   const onSubmitForm = () => {
      emit('submit', form.transform(() => _.cloneDeep(values)), _.cloneDeep(values));
   };

   onBeforeMount(() => {
      initDefaultValue();
   });

   watchEffect(() => {
      _.forEach(_.keys(_.cloneDeep(props.modelValue.defaultValues)), handleSetValueFn);
   });

   watch(() => props.modelValue?.fields, () => {
      initDefaultValue();
   });

   watch(() => props.modelValue.onSubmit, (val) => {
      if (val === true) {
         emit('update:modelValue', {
            ..._.cloneDeep(props.modelValue),
            onSubmit: false,
         });
         return;
      }

      onSubmitForm();
   });

   defineExpose({ values, form });
</script>
