<template>
   <div class='grow'>
      <!-- Panel body -->
      <div class='p-6 space-y-6'>
         <h2 class='text-2xl text-slate-800 font-bold mb-5'>API Access</h2>

         <section>
            <v-dynamic-form ref='dynamicFormRef' v-model='payload' @submit='handleSubmitForm' />
         </section>
      </div>
      <!-- Panel footer -->
      <footer>
         <div class='flex flex-col px-6 py-5 border-t border-slate-200'>
            <div class='flex self-end'>
               <v-button type='button'
                         variant='primary'
                         :loading='dynamicFormRef?.form?.processing'
                         @click='() => onSubmitForm()'
               >
                  Create new API key
               </v-button>
            </div>
         </div>
      </footer>
   </div>
</template>

<script setup lang='ts'>
   import { watchEffect } from 'vue';
   import { useDynamicForm } from '~/uses';
   import { useRoute } from '~/utils';

   import type Form from '~/types/Components/Form';

   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';
   import DynamicForm = Form.DynamicForm;

   const props = defineProps<{
      api_key?: string
   }>();

   const { dynamicFormRef, payload, setDefaultValue, onSubmitForm } = useDynamicForm({
      api_key: {
         allowChange: true,
         attrs: {
            label: 'API Key',
            type: 'text',
            required: false,
            disabled: true,
         },
      },
   }, {
      api_key: props?.api_key,
   });

   watchEffect(() => {
      if (props.api_key) {
         setDefaultValue('api_key', props.api_key);
      }
   });

   const handleSubmitForm: DynamicForm.HandleSubmitForm = form => {
      return form.post(useRoute('account.api'));
   };
</script>
