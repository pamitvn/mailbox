<template>
   <the-head>
      <title>Create New User</title>
   </the-head>
   <crud-layout title='Create New User' :has-per-page='false'>
      <template #header-action>
         <the-link :href='$route("admin.user.index")'>
            <v-button variant='dark' size='sm' outline icon>
               <template #icon>
                  <svg class='w-full h-full' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
                     <g fill='none' class='nc-icon-wrapper'>
                        <path d='M21 11H6.83l3.58-3.59L9 6l-6 6 6 6 1.41-1.41L6.83 13H21v-2z'
                              fill='currentColor'></path>
                     </g>
                  </svg>
               </template>
               Back to list
            </v-button>
         </the-link>
      </template>
      <div class='bg-white rounded-lg shadow-xl w-full max-w-md mx-auto p-6 pt-4'>
         <v-dynamic-form ref='dynamicFormRef' v-model='payload' @submit='handleSubmitForm' />

         <div class='mt-6 flex justify-center'>
            <v-button type='button'
                      variant='primary'
                      outline
                      class='px-12'
                      :loading='dynamicFormRef?.form?.processing'
                      @click='() => onSubmitForm()'
            >
               Create
            </v-button>
         </div>
      </div>
   </crud-layout>
</template>

<script setup lang='ts'>
   import { useDynamicForm } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Form } from '~/types/Components/Form';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';

   const { dynamicFormRef, payload, onSubmitForm } = useDynamicForm({
      name: {
         attrs: {
            required: true,
            label: 'Name',
            type: 'text',
            placeholder: 'Enter name',
         },
      },
      username: {
         attrs: {
            required: true,
            label: 'Username',
            type: 'text',
            placeholder: 'Enter username',
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
      password: {
         attrs: {
            required: true,
            label: 'Password',
            type: 'password',
            placeholder: 'Enter password',
         },
      },
   }, {
      name: null,
      username: null,
      email: null,
      password: null,
   });

   const handleSubmitForm: Form.DynamicForm.HandleSubmitForm = (form, values, resetForm) => {
      return form.post(useRoute('admin.user.store'), {
         forceFormData: true,
         onSuccess: () => {
            resetForm();
         },
      });
   };
</script>
