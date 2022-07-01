<template>
   <teleport to='body'>
      <div ref='modalRef'
           class='modal fade'
           tabindex='-1'
           role='dialog'
           aria-hidden='true'
           v-bind='attrs'
      >
         <slot></slot>
      </div>
   </teleport>
</template>

<script setup lang='ts'>
   import { computed, onMounted, onUnmounted, ref, useAttrs, watch } from 'vue';
   import Modal from '~/types/Components/Modal';

   interface Props {
      show?: boolean;
   }

   const attrs = useAttrs();
   const props = withDefaults(defineProps<Props>(), {
      show: false,
   });
   const emit = defineEmits(['interface']);

   const modalRef = ref<HTMLElement | null>(null);
   const modalBootstrap = ref<Modal.Bootstrap | null>(null);

   const isShow = computed(() => {
      // @ts-ignore
      const currentShow = modalBootstrap.value?._isShown;

      return currentShow === null ? false : currentShow;
   });

   const onShow = () => {
      if (isShow.value) return;

      modalBootstrap.value?.show();
   };
   const onHide = () => {
      if (!isShow.value) return;

      modalBootstrap.value?.hide();
   };
   const onDispose = () => {
      try {
         if (!isShow.value) return;

         modalBootstrap.value?.dispose();
      } catch (e) {

      }
   };

   watch(() => props.show, (val) => {
      if (val) onShow();
      else onHide();
   });

   onMounted(() => {
      if (!modalRef.value) return;

      // @ts-ignore
      modalBootstrap.value = bootstrap.Modal.getOrCreateInstance(modalRef.value);

      if (props.show) onShow();

      modalRef.value.addEventListener('hidden.bs.modal', (evt) => {
         try {
            document.body.removeChild(<Node>evt.target);
         } catch (e) {
         }
      });

      emit('interface', modalBootstrap.value);
   });

   onUnmounted(() => {
      onHide();
   });
</script>
