<template>
   <slot name='label' :label='label' :id='inputId'>
      <label class='small mb-1' :for='inputId'>{{ label }}</label>
   </slot>
   <slot name='input' :model='input' :attrs='$attrs' :class='getClass' :id='inputId'>
      <input v-model='input'
             v-bind='$attrs'
             :class='getClass'
             :id='inputId'
             @input='onInput'
      >
   </slot>
   <slot name='error' :error='error'>
      <div v-if='error' class='invalid-feedback'>
         {{ error }}
      </div>
   </slot>
</template>

<script>
   import { computed, defineComponent, ref, watch } from 'vue';

   export default defineComponent({
      inheritAttrs: true,
      name: 'TheInputField',
      props: {
         modelValue: {
            type: [String, Number],
            default: '',
         },
         label: String,
         error: String,
         allowChange: {
            type: Boolean,
            default: true,
         },
      },
      emits: ['update:modelValue', 'input'],
      setup(props, { emit, attrs }) {
         const input = ref(props.modelValue ?? '');
         const inputId = `input-field-${Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5)}`;

         const inputProp = computed(() => props.modelValue);
         const getClass = computed(() => ({
            [attrs.class ?? '']: true,
            'form-control': true,
            'is-invalid': !!props.error,
         }));

         const onInput = (...rest) => emit('input', ...rest);

         watch(() => inputProp.value, () => {
            if (!props.allowChange) return;

            input.value = props.modelValue;
         });
         watch(input, (val) => emit('update:modelValue', val));

         return { input, inputId, getClass, onInput };
      },
   });
</script>
