<template>
   <teleport to='body'>
      <v-modal :open='openModal' :title='`View Product of Order #${order?.id}`' @close-modal='onClose'>
         <div class='p-6'>
            <v-textarea v-model='payload' :rows='16' readonly></v-textarea>
         </div>
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import axios from 'axios';
   import { ref, watchEffect } from 'vue';

   import { useRoute } from '~/utils';
   import { useToast } from '~/uses';
   import { Models } from '~/types/Models';

   import VModal from '~/components/VModal.vue';
   import VTextarea from '~/components/Form/VTextarea.vue';

   const props = defineProps<{
      order?: Models.Order | null
   }>();
   const emit = defineEmits(['update:order']);

   const loading = ref(false);
   const openModal = ref(false);
   const payload = ref('');

   const onClose = () => {
      openModal.value = false;
      payload.value = '';
      emit('update:order', null);
   };

   const fetchPayload = async () => {
      loading.value = true;

      const id = props.order?.id;

      if (!id) return;

      const url = useRoute('product.export', { action: 'view' });
      const data = { includes: [id] };

      try {

         if (props.order?.expired) {
            payload.value = 'This transaction has expired (24 hours) for data query';
            loading.value = false;
            return;
         }

         const { data: responseData } = await axios.post(url, data);

         let output = '';

         _.forEach(responseData || [], (item: Models.Product) => {
            output += `${item.mail}|${item.password}${item.recovery_mail ? `|${item.recovery_mail}` : ''}` + '\n';
         });

         payload.value = output;
      } catch (e) {
         useToast(`There was a problem getting the product.`, {
            type: 'danger',
         });
      } finally {
         loading.value = false;
      }
   };

   watchEffect(async () => {
      openModal.value = false;
      if (props.order) {
         payload.value = '';
         await fetchPayload();
         openModal.value = true;
      }
   });

   defineExpose({ loading });

</script>
