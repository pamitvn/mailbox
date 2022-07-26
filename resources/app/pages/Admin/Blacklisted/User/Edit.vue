<template>
   <the-head>
      <title>Edit Blacklisted User #{{ props.blacklisted?.id }}</title>
   </the-head>
   <crud-layout :title='`Edit Blacklisted User #${props.blacklisted?.id}`' :has-per-page='false'>
      <template #header-action>
         <the-link :href='$route("admin.blacklisted.user.index")'>
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
               Save change
            </v-button>
         </div>
      </div>
   </crud-layout>
</template>

<script setup lang='ts'>
   import dateFormat from 'dateformat';

   import { useDynamicForm } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Form } from '~/types/Components/Form';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';

   const props = defineProps<{
      blacklisted: Models.Blacklisted
   }>();

   const { dynamicFormRef, payload, onSubmitForm } = useDynamicForm({
      user_id: {
         attrs: {
            required: true,
            disabled: true,
            label: 'User',
            type: 'text',
         },
      },
      reason: {
         attrs: {
            required: true,
            label: 'Reason',
            type: 'text',
            placeholder: 'Enter reason',
         },
      },
      duration: {
         attrs: {
            required: true,
            label: 'Duration',
            type: 'date',
            placeholder: 'Enter duration',
         },
      },
   }, {
      user_id: `[${props.blacklisted?.user?.username}] ${props.blacklisted?.user?.name}`,
      reason: props.blacklisted?.reason,
      duration: props.blacklisted.duration ? dateFormat(props.blacklisted.duration ?? '', 'yyyy-mm-dd') : null,
   });

   const handleSubmitForm: Form.DynamicForm.HandleSubmitForm = (form, values, resetForm) => {
      return form.put(useRoute('admin.blacklisted.user.update', props.blacklisted.id), {
         preserveState: true,
      });
   };
</script>
