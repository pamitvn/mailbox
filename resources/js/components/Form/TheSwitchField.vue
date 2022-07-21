<template>
   <div class='form-check form-switch'>
      <slot v-if='label' name='label' :label='label' :id='inputId'>
         <label class='form-check-label' :for='inputId'>
            {{ label }}
         </label>
      </slot>
      <slot name='input' :model='input' :attrs='$attrs' :class='getClass' :id='inputId'>
         <input v-model='input'
                v-bind='$attrs'
                type='checkbox'
                :class='getClass'
                :id='inputId'
                @input='onInput'
         >
      </slot>
      <slot v-if='error' name='error' :error='error'>
         <div class='invalid-feedback'>
            {{ error }}
         </div>
      </slot>
   </div>
</template>

<script lang='ts'>
   import { computed, defineComponent, ref, watch } from 'vue';

   export default defineComponent({
      inheritAttrs: true,
      name: 'TheCheckBoxField',
      props: {
         modelValue: {
            type: [Boolean, Number],
            default: false,
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
         const input = ref(props.modelValue);
         const inputId = `input-field-${Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5)}`;

         const inputProp = computed(() => props.modelValue);
         const getClass = computed(() => ({
            [attrs.class ?? '']: true,
            'form-check-input': true,
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
