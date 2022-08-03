<template>
   <div class='grow'>
      <!-- Panel body -->
      <div class='p-6 space-y-6'>
         <h2 class='text-2xl text-slate-800 font-bold mb-5'>Reset Password</h2>

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
                  Save Changes
               </v-button>
            </div>
         </div>
      </footer>
   </div>
</template>

<script setup lang='ts'>
   import { useDynamicForm } from '~/uses';
   import { useRoute } from '~/utils';

   import type Form from '~/types/Components/Form';

   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';
   import DynamicForm = Form.DynamicForm;

   const { dynamicFormRef, payload, onSubmitForm } = useDynamicForm({
      current_password: {
         attrs: {
            required: true,
            label: 'Current Password',
            type: 'password',
            placeholder: 'Enter current password',
         },
      },
      password: {
         attrs: {
            required: true,
            label: 'Password',
            type: 'password',
            placeholder: 'Enter password',
         },
      },
      password_confirmation: {
         attrs: {
            required: true,
            label: 'Confirm Password',
            type: 'password',
            placeholder: 'Enter confirm password',
         },
      },
   }, {
      current_password: null,
      password: null,
      password_confirmation: null,
   });

   const handleSubmitForm: DynamicForm.HandleSubmitForm = form => {
      return form.put(useRoute('account.reset-password'), {
         onSuccess: () => {
            form.reset();
         },
      });
   };
</script>
