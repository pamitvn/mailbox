<template>
   <div class='flex items-center'>
      <div class='form-switch'>
         <v-input type='input' v-bind='attrs' label='' v-slot='{classname}'>
            <input v-model='input'
                   ref='inputRef'
                   type='checkbox'
                   class='sr-only'
                   :class='classname'
            />
            <label :class='{"bg-slate-400": !input, "error": attrs.error}' v-on='onLabel'>
               <span class='bg-white shadow-sm' aria-hidden='true'></span>
               <span class='sr-only'>Toggle</span>
            </label>
         </v-input>
      </div>
      <div v-if='attrs?.placeholder || attrs?.label'
           class='text-sm italic ml-2 cursor-pointer select-none'
           v-on='onLabel'>
         {{ attrs?.placeholder || attrs?.label }}
      </div>

      <div v-if='error' class='ml-2 text-xs mt-1 text-rose-500'>
         {{ error }}
      </div>
   </div>
</template>

<script setup lang='ts'>
   import { ref, useAttrs, watch } from 'vue';
   import VInput from '~/components/Form/VInput.vue';

   const props = defineProps<{
      modelValue?: boolean
      error?: string
   }>();
   const emit = defineEmits(['update:modelValue']);
   const attrs = useAttrs();

   const input = ref(props.modelValue || false);
   const inputRef = ref<HTMLInputElement | null>(null);

   const onLabel = {
      click: () => input.value = !input.value,
   };

   watch(input, (val) => {
      emit('update:modelValue', val);
   });
</script>

<style lang='scss' scoped>
   /* Switch element */
   .form-switch {
      @apply relative select-none;
      width: 44px;
   }

   .form-switch label {
      @apply block overflow-hidden cursor-pointer h-6 rounded-full;
   }

   .form-switch label > span:first-child {
      @apply absolute block rounded-full;
      width: 20px;
      height: 20px;
      top: 2px;
      left: 2px;
      right: 50%;
      transition: all .15s ease-out;
   }

   .form-switch input[type="checkbox"]:checked + label {
      @apply bg-indigo-500;
   }

   .form-switch input[type="checkbox"]:checked + label > span:first-child {
      left: 22px;
   }

   .form-switch input[type="checkbox"]:disabled + label {
      @apply cursor-not-allowed bg-slate-100 border border-slate-200;
   }

   .form-switch input[type="checkbox"]:disabled + label > span:first-child {
      @apply bg-slate-400;
   }

   .error {
      @apply border-rose-300;
   }
</style>
