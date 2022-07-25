<template>
   <button
      v-bind='attrs'
      :class='classname'
      v-on='events'
      :disabled='loading'
   >
      <span v-if='loading' :class='{"mr-2": showContent}'>
         <svg class='animate-spin w-4 h-4 fill-current shrink-0' viewBox='0 0 16 16'><path
            d='M8 16a7.928 7.928 0 01-3.428-.77l.857-1.807A6.006 6.006 0 0014 8c0-3.309-2.691-6-6-6a6.006 6.006 0 00-5.422 8.572l-1.806.859A7.929 7.929 0 010 8c0-4.411 3.589-8 8-8s8 3.589 8 8-3.589 8-8 8z'></path></svg>
      </span>
      <span v-else-if='onlyIcon || icon' class='w-4 h-4 fill-current shrink-0'
            :class='{"mr-2": showContent}'>
         <slot name='icon'></slot>
      </span>
      <slot v-if='showContent'></slot>
   </button>
</template>

<script setup lang='ts'>
   import { computed, useAttrs } from 'vue';
   import { useMobile } from '~/uses';

   type ButtonType = 'button' | 'submit' | 'reset';
   type VariantType =
      'none'
      | 'primary'
      | 'secondary'
      | 'danger'
      | 'success'
      | 'warning'
      | 'info'
      | 'light'
      | 'dark'
      | string;
   type ButtonSize = 'xs' | 'sm' | 'md' | 'lg';

   const props = withDefaults(defineProps<{
      events?: {
         [event: string]: Function
      }
      type?: ButtonType
      variant?: VariantType
      size?: ButtonSize
      outline?: boolean
      block?: boolean
      pill?: boolean
      squared?: boolean
      icon?: boolean
      onlyIcon?: boolean
      justMobileIcon?: boolean
      loading?: boolean
   }>(), {
      events: {} as any,
      type: 'button',
      variant: 'primary',
      size: 'md',
      outline: false,
      block: false,
      pill: false,
      squared: false,
      icon: false,
      onlyIcon: false,
      loading: false,
   });
   const attrs = useAttrs();

   const { isMobile } = useMobile();

   const classname = computed(() => {
      const isNotNone = props.variant !== 'none';
      const buttonSize = props.size === 'md' ? 'btn' : `btn-${props.size}`;

      return {
         [buttonSize]: isNotNone,
         'btn__block': props.block,
         'btn__pill': props.pill,
         'btn__squared': props.squared,
         [`variant__${props.variant}`]: isNotNone && !props.outline,
         [`variant__outline--${props.variant}`]: isNotNone && props.outline,
         'btn__disabled': props.loading,
      };
   });
   const showContent = computed(() => {
      return props.justMobileIcon
         ? !isMobile.value
         : !props.onlyIcon;
   });
</script>

<style lang='scss' scoped>
   .btn {
      @apply text-white;

      &__ {
         &block {
            @apply block w-full
         }

         &pill {
            @apply rounded-full
         }

         &squared {
            @apply rounded-none;
         }

         &disabled {
            @apply hover:bg-white disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed shadow-none;
         }
      }

      &[disabled] {
         @apply hover:bg-transparent disabled:border-slate-200 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed shadow-none;
      }
   }

   .variant__ {
      &primary {
         @apply bg-indigo-500 hover:bg-indigo-600 text-white;
      }

      &secondary {
         @apply bg-gray-500 hover:bg-gray-600 text-white;
      }

      &success {
         @apply bg-green-500 hover:bg-green-600 text-white;
      }

      &danger {
         @apply bg-red-600 hover:bg-red-700 text-white;
      }

      &warning {
         @apply bg-yellow-500 hover:bg-yellow-600 text-white;
      }

      &info {
         @apply bg-cyan-500 hover:bg-cyan-600 text-white;
      }

      &light {
         @apply bg-white hover:bg-gray-300 text-black;
      }

      &dark {
         @apply bg-black hover:bg-slate-900 text-white;
      }
   }

   .variant__outline-- {
      background-color: unset;

      &primary {
         @apply border-indigo-400 hover:bg-indigo-600 text-indigo-500 hover:text-white;
      }

      &secondary {
         @apply border-gray-400 hover:bg-gray-600 text-gray-500 hover:text-white;
      }

      &success {
         @apply border-green-400 hover:bg-green-600 text-green-500 hover:text-white;
      }

      &danger {
         @apply border-red-400 hover:bg-red-600 text-red-500 hover:text-white;
      }

      &warning {
         @apply border-yellow-400 hover:bg-yellow-600 text-yellow-500 hover:text-black;
      }

      &info {
         @apply border-cyan-400 hover:bg-cyan-600 text-cyan-500 hover:text-white;
      }

      &light {
         @apply border-white hover:bg-white hover:text-black;
      }

      &dark {
         @apply border-gray-900 hover:bg-slate-900 hover:text-white text-gray-900;
      }
   }
</style>
