<template>
   <!-- Modal backdrop -->
   <transition
      enter-active-class='transition ease-out duration-200'
      enter-from-class='opacity-0'
      enter-to-class='opacity-100'
      leave-active-class='transition ease-out duration-100'
      leave-from-class='opacity-100'
      leave-to-class='opacity-0'
   >
      <div v-show='open' class='fixed inset-0 bg-slate-900 bg-opacity-30 z-50 transition-opacity'
           aria-hidden='true'></div>
   </transition>
   <!-- Modal dialog -->
   <transition
      enter-active-class='transition ease-in-out duration-200'
      enter-from-class='opacity-0 translate-y-4'
      enter-to-class='opacity-100 translate-y-0'
      leave-active-class='transition ease-in-out duration-200'
      leave-from-class='opacity-100 translate-y-0'
      leave-to-class='opacity-0 translate-y-4'
   >
      <div v-if='open'
           class='fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6' role='dialog'
           aria-modal='true'
           v-bind='attrs'
      >
         <div ref='modalWrapper'
              class='bg-white rounded shadow-lg'
              :class='getWrapperClass'
         >
            <!-- Modal header -->
            <slot v-if='hasHeader' name='header' v-bind='{onClose}'>
               <div class='px-5 py-3 border-b border-slate-200'>
                  <div class='flex justify-between items-center'>
                     <slot name='header-title' v-bind='{title}'>
                        <div class='font-semibold text-slate-800'>{{ title }}</div>
                     </slot>
                     <slot v-if='hasHeaderClose' name='header-close' v-bind='{onClose}'>
                        <button class='text-slate-400 hover:text-slate-500' @click.stop='onClose'>
                           <div class='sr-only'>Close</div>
                           <svg class='w-4 h-4 fill-current'>
                              <path
                                 d='M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z' />
                           </svg>
                        </button>
                     </slot>
                  </div>
               </div>
            </slot>
            <slot v-bind='{onClose}' />
         </div>
      </div>
   </transition>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, useAttrs, watch } from 'vue';
   import { onClickOutside, onKeyStroke } from '@vueuse/core';

   type ScreenSizeType = 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl';
   type MaxHeightType = 'full' | 'screen' | 'min' | string | number;

   const props = withDefaults(defineProps<{
      title?: string
      wrapperClass?: string | object

      maxWidth?: ScreenSizeType
      maxHeight?: MaxHeightType

      fullHeight?: boolean
      fullWidth?: boolean

      open?: boolean
      hasHeader?: boolean
      hasHeaderClose?: boolean
      hasScrollBar?: boolean
   }>(), {
      maxWidth: 'lg',
      maxHeight: 'full',
      fullHeight: false,
      fullWidth: true,
      open: false,
      hasHeader: true,
      hasHeaderClose: true,
      hasScrollBar: false,
   });
   const emit = defineEmits(['update:open', 'close-modal']);
   const attrs = useAttrs();

   const open = ref<boolean>(props.open || false);
   const modalWrapper = ref<HTMLElement | null>(null);

   const getWrapperClass = computed(() => {
      const classname = _.isString(props.wrapperClass)
         ? { [props.wrapperClass]: true }
         : props.wrapperClass;

      return {
         'overflow-auto': props.hasScrollBar,
         [`max-w-${props.maxWidth}`]: true,
         [`max-h-${props.maxHeight}`]: true,
         'w-full': props.fullWidth,
         'h-full': props.fullHeight,
         ...classname,
      };
   });

   const onClose = () => {
      open.value = false;
   };
   const clickHandler = ({ target }) => {
      if (!open.value || modalWrapper.value.contains(target)) return;

      open.value = false;
   };
   const keyHandler = ({ keyCode }) => {
      if (!open.value || keyCode !== 27) return;

      open.value = false;
   };

   onClickOutside(modalWrapper, (e: PointerEvent) => {
      if (!open.value) return;
      open.value = false;
   });

   onKeyStroke(['Esc', 'Escape'], (e) => {
      if (!open.value) return;
      open.value = false;
   });

   watch(() => props.open, (val) => {
      open.value = val;
   });

   watch(open, val => {
      emit('update:open', val);

      if (!val) emit('close-modal');
   });
</script>
