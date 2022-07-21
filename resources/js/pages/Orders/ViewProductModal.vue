<template>
   <teleport to='body'>
      <div ref='modalRef'
           class='modal fade'
           id='view-product-model'
           tabindex='-1'
           role='dialog'
           aria-hidden='true'
           data-bs-backdrop='static'
           data-bs-keyboard='false'
      >
         <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
               <div class='modal-header'>
                  <h5 class='modal-title'>View Product of Order #{{ order?.id }}</h5>
                  <button class='btn-close' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
               </div>

               <div class='modal-body'>
                  <textarea v-model='payload' class='form-control w-100' rows='15'></textarea>
               </div>
            </div>
         </div>
      </div>
   </teleport>
</template>

<script setup lang='ts'>
   import { onMounted, onUnmounted, ref, watchEffect } from 'vue';
   import { Models } from '~/types';
   import Modal from '~/types/Components/Modal';
   import { useRoute } from '~/utils';
   import axios from 'axios';
   import { useToast } from '~/uses';
   import _ from 'lodash';

   const props = defineProps<{
      order: Models.Order | null
   }>();
   const emit = defineEmits(['update:order']);

   const modalRef = ref<HTMLElement | null>(null);
   const modalBootstrap = ref<Modal.Bootstrap | null>(null);
   const payload = ref('');

   const forgetModel = () => {
      if (!modalBootstrap.value.hide) return;

      modalBootstrap.value?.hide();
      emit('update:order', null);
   };
   const fetchPayload = async () => {
      const id = props.order?.id;

      if (!id) return;

      const url = useRoute('product.export', { action: 'view' });
      const data = { includes: [id] };

      try {
         const { data: responseData } = await axios.post(url, data);

         let output = '';

         _.forEach(responseData || [], (item: Models.Product) => {
            output += `${item.mail}|${item.password}${item.recovery_mail ? `|${item.recovery_mail}` : ''}` + '\n';
         });

         payload.value = output;
      } catch (e) {
         useToast(`An problem occurred during the download.`, {
            type: 'danger',
         });
      }
   };

   watchEffect(async () => {
      if (props.order) {
         payload.value = '';
         await fetchPayload();
      }
   });


   onMounted(() => {
      // @ts-ignore
      modalBootstrap.value = bootstrap.Modal.getOrCreateInstance('#view-product-model');

      modalRef.value.addEventListener('hidden.bs.modal', () => forgetModel());

      onUnmounted(() => {
         forgetModel();
      });
   });

</script>
