<template>
   <slot v-if='label' name='label' :event='onLabel'>
      <label class='block text-sm font-medium mb-1'
             v-on='onLabel'
      >
         {{ label }}
         <span v-if='required' class='text-rose-500'>*</span>
      </label>
   </slot>
   <slot v-bind='{event: onInput, attrs, classname }'>
      <input
         v-bind='attrs'
         ref='inputRef'
         :class='classname'
         :type='type'
         :value='input'
         v-on='onInput'
      />
   </slot>
   <div v-if='error || helper' class='text-xs mt-1'
        :class='{"text-rose-500": !!error}'
   >
      {{ error ?? helper }}
   </div>
</template>

<script setup lang='ts'>
   import { computed, ref, useAttrs, watch } from 'vue';
   import { Form } from '~/types/Components/Form';

   interface Props {
      label?: string;
      modelValue?: string | number;
      helper?: string;
      error?: string;
      allowChange?: boolean;

      required?: boolean;

      type: Form.Input.BaseType;
      BSize?: Form.Input.Size;
      BStyle?: Form.Input.Style;

      [attrs: string]: any;
   }

   const props = withDefaults(defineProps<Props>(), {
      type: 'text',
      BSize: 'default',
      BStyle: 'simple',
      allowChange: true,
      required: false,
   });
   const emit = defineEmits(['update:modelValue', 'input']);
   const attrs = useAttrs();

   const input = ref<string | number | boolean | null>(props.modelValue ?? '');
   const inputRef = ref<HTMLElement | null>(null);

   const inputProp = computed(() => props.modelValue);
   const classname = computed(() => {
      return {
         'w-full': true,
         'form-input': props.BStyle !== 'none',
         [`size__${props.BSize}`]: true,
         [`style__${props.BStyle}`]: true,
         'error': !!props.error,
      };
   });

   const onLabel = {
      click: () => inputRef.value?.focus(),
   };
   const onInput = {
      input: (e: InputEvent) => {
         const inputElement: HTMLInputElement = e?.target as HTMLInputElement;

         input.value = inputElement?.value;

         emit('update:modelValue', input.value);
         emit('input', e);
      },
   };

   watch(() => inputProp.value, () => {
      if (!props.allowChange) return;

      input.value = props.modelValue;
   });

</script>

<style lang='scss' scoped>
   .size__ {
      &small {
         @apply px-2 py-1;
      }

      &large {
         @apply px-4 py-3;
      }
   }

   .style__ {
      &simple {
         @apply mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50;
      }

      &underline {
         @apply mt-0 px-0.5 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-black;
      }

      &solid {
         @apply mt-1 rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0;
      }

   }

   .error {
      @apply border-rose-300;
   }

   input[disabled] {
      @apply disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed
   }
</style>
