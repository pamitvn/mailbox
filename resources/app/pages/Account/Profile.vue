<template>
   <div class='grow'>
      <!-- Panel body -->
      <div class='p-6 space-y-6'>
         <h2 class='text-2xl text-slate-800 font-bold mb-5'>My Account</h2>

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

   import type { Models } from '~/types/Models';
   import type Form from '~/types/Components/Form';

   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';
   import DynamicForm = Form.DynamicForm;

   const props = defineProps<{
      user: Models.User
   }>();

   const { dynamicFormRef, payload, onSubmitForm } = useDynamicForm({
      name: {
         attrs: {
            required: true,
            label: 'Full Name',
            type: 'text',
            placeholder: 'Enter full name',
         },
      },
      username: {
         attrs: {
            required: true,
            label: 'Username',
            type: 'text',
            disabled: true,
         },
      },
      email: {
         attrs: {
            required: true,
            label: 'Email',
            type: 'email',
            placeholder: 'Enter email address',
         },
      },
      balance: {
         attrs: {
            label: 'Balance',
            type: 'number',
            disabled: true,
         },
      },
   }, {
      name: props.user?.name,
      email: props.user?.email,
      username: props.user?.username,
      balance: props.user?.balance,
   });

   const handleSubmitForm: DynamicForm.HandleSubmitForm = form => {
      return form.put(useRoute('account.profile'));
   };
</script>
