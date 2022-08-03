<template>
   <div class='relative'>
      <button
         ref='trigger'
         class='btn justify-between min-w-44 bg-white border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-600'
         aria-label='Select date range'
         aria-haspopup='true'
         @click.prevent='dropdownOpen = !dropdownOpen'
         :aria-expanded='dropdownOpen'
      >
      <span class='flex items-center'>
         <slot name='icon'></slot>
        <span>{{ !selected ? label : getOptions[selected] }}</span>
      </span>
         <svg class='shrink-0 ml-1 fill-current text-slate-400' width='11' height='7' viewBox='0 0 11 7'>
            <path d='M5.4 6.8L0 1.4 1.4 0l4 4 4-4 1.4 1.4z' />
         </svg>
      </button>
      <transition
         enter-active-class='transition ease-out duration-100 transform'
         enter-from-class='opacity-0 -translate-y-2'
         enter-to-class='opacity-100 translate-y-0'
         leave-active-class='transition ease-out duration-100'
         leave-from-class='opacity-100'
         leave-to-class='opacity-0'
      >
         <div v-show='dropdownOpen'
              class='z-10 absolute top-full right-0 w-full bg-white border border-slate-200 py-1.5 rounded shadow-lg overflow-hidden mt-1'>
            <div
               ref='dropdown'
               class='font-medium text-sm text-slate-600'
               @focusin='dropdownOpen = true'
               @focusout='dropdownOpen = false'
            >

               <button
                  v-if='label'
                  class='flex items-center w-full hover:bg-slate-50 py-1 px-3 cursor-pointer'
               >
                  <span>{{ label }}</span>
               </button>

               <button
                  v-for='(option, key) in getOptions'
                  :key='key'
                  class='flex items-center w-full hover:bg-slate-50 py-1 px-3 cursor-pointer'
                  :class="isSelected(key, selected) && 'text-indigo-500'"
                  @click='selected = key; dropdownOpen = false'
               >
                  <svg class='shrink-0 mr-2 fill-current text-indigo-500'
                       :class="!isSelected(key, selected) && 'invisible'"
                       width='12' height='9' viewBox='0 0 12 9'>
                     <path
                        d='M10.28.28L3.989 6.575 1.695 4.28A1 1 0 00.28 5.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28.28z' />
                  </svg>
                  <span>{{ option }}</span>
               </button>
            </div>
         </div>
      </transition>
   </div>
</template>

<script setup lang='ts'>
   import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
   import _ from 'lodash';

   const props = withDefaults(defineProps<{
      modelValue?: string | number
      label?: string
      pathToKey?: string
      pathToLabel?: string
      options: object | object[]
   }>(), {
      pathToKey: 'key',
      pathToLabel: 'label',
   });
   const emit = defineEmits(['update:modelValue']);

   const dropdownOpen = ref(false);
   const trigger = ref(null);
   const dropdown = ref(null);
   const selected = ref(props.modelValue || '');

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

   const isSelected = (key, target) => {
      return (key?.toString() || key) === (target?.toString() || target);
   };
   const clickHandler = ({ target }) => {
      if (!dropdownOpen.value || dropdown.value.contains(target) || trigger.value.contains(target)) return;
      dropdownOpen.value = false;
   };
   const keyHandler = ({ keyCode }) => {
      if (!dropdownOpen.value || keyCode !== 27) return;
      dropdownOpen.value = false;
   };

   onMounted(() => {
      document.addEventListener('click', clickHandler);
      document.addEventListener('keydown', keyHandler);
   });

   onUnmounted(() => {
      document.removeEventListener('click', clickHandler);
      document.removeEventListener('keydown', keyHandler);
   });

   watch(selected, (val) => {
      emit('update:modelValue', val);
   });

</script>
