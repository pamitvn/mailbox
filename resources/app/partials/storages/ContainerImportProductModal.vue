<template>
   <v-button outline @click='show'>
      Import
   </v-button>
   <teleport to='body'>
      <v-modal v-model:open='open' title='Import product'>
         <div class='p-6 mb-3'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='grid grid-col gap-4'>
                  <div>
                     <v-switch v-model='form.importByFile'
                               label='Import by form'
                     ></v-switch>
                  </div>
                  <div v-if='form.importByFile'>
                     <v-input v-model='form.file'
                              :error='form.errors.file'
                              type='file'
                              accept='.txt'
                              placeholder='Only allow txt file'
                              label='Upload file'
                     >

                     </v-input>
                  </div>
                  <div v-else>
                     <v-textarea v-model='form.products'
                                 :error='form.errors.file'
                                 label='Products'
                                 :rows='10'
                     />
                  </div>
               </div>

               <div class='mt-4 flex justify-center'>
                  <v-button outline class='px-12'
                            :loading='form.processing'
                            :disabled='isDisabled'
                  >
                     Import
                  </v-button>
               </div>
            </form>
         </div>
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, watch } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';

   import { useRoute } from '~/utils';
   import { useModal } from '~/uses';

   import type { Models } from '~/types/Models';

   import VTextarea from '~/components/Form/VTextarea.vue';
   import VSwitch from '~/components/Form/VSwitch.vue';
   import VInput from '~/components/Form/VInput.vue';
   import VButton from '~/components/VButton.vue';
   import VModal from '~/components/VModal.vue';

   const props = defineProps<{
      storage: Models.Storage
   }>();

   const { open, show } = useModal();
   const form = useForm({
      importByFile: false,
      products: '',
      file: null,
   });

   const isDisabled = computed(() => !form.file && !form.products.trim());

   const onSubmitForm = () => {
      return form.transform((data) => ({
         ...(data.file ? {
            file: data.file,
         } : {
            products: _.compact(data.products.trim().split('\n')),
         }),
      })).post(useRoute('storage.container.store', { storage: props.storage.id }), {
         onSuccess: () => {
         },
      });
   };

   watch(() => form.importByFile, () => {
      form.products = '';
      form.file = null;
   });
</script>
