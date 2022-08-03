<template>
   <teleport to='body'>
      <v-modal v-model:open='open' :title='`Export "${storage?.name}" storage`' max-width='xs'>
         <div class='p-6 mb-3'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='grid grid-col gap-4'>
                  <div>
                     <v-select v-model='form.status'
                               :error='form.errors.status'
                               :options='statusOptions'
                               label='Status'
                               placeholder='Select status'
                               helper='Empty to export all status'
                     />
                  </div>
                  <div>
                     <v-switch v-model='form.deleteAfterExport'
                               label='Delete after export'
                     ></v-switch>
                  </div>
               </div>

               <div class='mt-4 flex justify-center'>
                  <v-button outline class='px-12'
                            :loading='loading'
                  >
                     Export
                  </v-button>
               </div>
            </form>
         </div>
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, watch } from 'vue';
   import axios from 'axios';
   import fileSaver from 'file-saver';
   import { Inertia } from '@inertiajs/inertia';
   import { useForm, usePage } from '@inertiajs/inertia-vue3';

   import { useRoute } from '~/utils';
   import { useModal, useToast } from '~/uses';

   import type { Models } from '~/types/Models';

   import VSwitch from '~/components/Form/VSwitch.vue';
   import VButton from '~/components/VButton.vue';
   import VModal from '~/components/VModal.vue';
   import VSelect from '~/components/Form/VSelect.vue';

   const props = defineProps<{
      storage: Models.Storage | null
   }>();
   const emit = defineEmits(['update:storage']);

   const loading = ref(false);
   const statusOptions = computed(() => _.get(usePage().props.value, 'statusLabel', []));

   const { open, show, close } = useModal({
      onClose: () => {
         emit('update:storage', null);
      },
   });
   const form = useForm({
      deleteAfterExport: false,
      status: null,
   });

   const onSubmitForm = async () => {
      const url = useRoute('storage.export', { storage: props.storage.id });

      try {
         loading.value = true;
         const response = await axios.post(url, form.data(), {
            responseType: 'arraybuffer',
            headers: {
               'Accept': 'text/plain',
            },
         });

         const blob = new Blob([response.data], {
            type: 'text/plain;charset=utf-8',
         });

         await fileSaver.saveAs(blob, `[${new Date().toISOString()}] Export items of container ${props.storage.name} (MailBox).txt`);

         close();

         Inertia.reload();
      } catch (e) {
         useToast(`An problem occurred during the download.`, {
            type: 'danger',
         });
      } finally {
         loading.value = false;
      }
   };

   watch(() => props.storage, (val) => {
      val
         ? show()
         : close();
   });
</script>
