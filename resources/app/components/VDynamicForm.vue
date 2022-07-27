<template>
   <form @submit.prevent='() => onSubmitForm()'>
      <slot
         v-for='field in getFields'
         :key='field.key'
         :name='field.key'
         :attrs='getAttrs(field, field.key)'
         :events='getEvent(field, field.key)'
      >
         <transition
            enter-active-class='transition ease-out duration-300 transform'
            enter-from-class='opacity-0 -translate-y-2'
            enter-to-class='opacity-100 translate-y-0'
            leave-active-class='transition ease-out duration-300'
            leave-from-class='opacity-100'
            leave-to-class='opacity-0'
         >
            <div v-if='field.show' class='mt-4 first:mt-0'>
               <component
                  :is='getComponent(field)'
                  v-model='values[field.key]'
                  v-bind='getAttrs(field, field.key)'
                  @keydown.stop='getEvent(field, field.key).keydown'
               ></component>
            </div>
         </transition>
      </slot>
   </form>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, onBeforeMount, reactive, watch, watchEffect } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';

   import type Form from '~/types/Components/Form';

   import VInput from '~/components/Form/VInput.vue';
   import DynamicForm = Form.DynamicForm;
   import { parseToBoolean } from '~/utils';

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

   const getFields = computed(() => {
      const list = _.cloneDeep(props.modelValue?.fields);

      return _.map(list, (ite, key) => {
         ite.show = true;

         if (ite.show_if && !parseToBoolean(values[ite.show_if])) ite.show = false;
         if (ite.show_unless && parseToBoolean(values[ite.show_unless])) ite.show = false;

         return { ...ite, key };
      });
   });

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
      emit('submit',
         form.transform(() => {
            const payloads = {};

            _.forEach(getFields.value, field => {
               _.set(payloads, field?.resolveKey ?? field.key, _.get(values, field.key));
            });

            return payloads;
         }),
         _.cloneDeep(values),
         resetForm,
      );
   };
   const resetForm = () => {
      const fields = props.modelValue?.fields;

      _.forEach(_.keys(fields), key => {
         const defaultValue = _.get(values, key as string);

         values[key] = _.get(props.modelValue.defaultValues, key, defaultValue);
      });
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
