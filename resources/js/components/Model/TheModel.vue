<template>
   <teleport to='body'>
      <div ref='modalRef'
           class='modal fade'
           :id='getId'
           tabindex='-1'
           role='dialog'
           :aria-labelledby='`${getId}-label`'
           aria-hidden='true'
           data-bs-backdrop='static'
           data-bs-keyboard='false'
      >
         <div class='modal-dialog modal-dialog-centered' role='document'>
            <slot name='body'>
               <slot name='header'>
                  <div class='modal-header'></div>
               </slot>

               <slot name='content'>
                  <div class='modal-content'></div>
               </slot>

               <slot name='footer'>
                  <div class='modal-footer'></div>
               </slot>
            </slot>
         </div>
      </div>

   </teleport>
</template>

<script setup lang='ts'>
   import { ref } from 'vue';
   import Model from '~/types/Components/Model';

   const props = withDefaults(defineProps<{}>(), {});

   const modalRef = ref<HTMLElement | null>(null);
   const modalBootstrap = ref<Model.Bootstrap | null>(null);
</script>
