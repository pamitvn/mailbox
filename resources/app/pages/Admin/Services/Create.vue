<template>
   <the-head><title>Create new Service</title></the-head>
   <crud-layout title='Create New Service' :has-per-page='false'>
      <template #header-action>
         <the-link :href='$route("admin.service.index")'>
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
   import _ from 'lodash';
   import { computed, watchEffect } from 'vue';
   import { useDynamicForm } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Form } from '~/types/Components/Form';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';

   const props = defineProps<{
      extraFields: {
         [key: string]: {
            rule: string
            show_unless?: string
            show_if?: string
            attribute: {
               [key: string]: any
            }
            [key: string]: any
         }
      }
   }>();

   const { dynamicFormRef, payload, onSubmitForm, setField } = useDynamicForm({
      name: {
         attrs: {
            required: true,
            label: 'Name',
            type: 'text',
            placeholder: 'Enter name',
         },
      },
      lifetime: {
         attrs: {
            required: true,
            label: 'Lifetime',
            type: 'text',
            placeholder: 'Enter lifetime',
         },
      },
      feature_image: {
         attrs: {
            required: true,
            label: 'Feature image',
            type: 'file',
            accept: 'image/*',
            placeholder: 'Service Feature image',
         },
      },
      price: {
         attrs: {
            required: true,
            label: 'Price',
            type: 'number',
            placeholder: 'Enter price',
         },
      },
      clean_after: {
         attrs: {
            required: false,
            label: 'Auto Clean After (Hours)',
            type: 'number',
            placeholder: 'Enter hours',
         },
      },
      pop3: {
         is: 'v-switch',
         attrs: {
            required: false,
            label: 'Pop3',
         },
      },
      imap: {
         is: 'v-switch',
         attrs: {
            required: false,
            label: 'IMAP',
         },
      },
      visible: {
         is: 'v-switch',
         attrs: {
            required: false,
            label: 'Visible',
         },
      },
      is_local: {
         is: 'v-switch',
         attrs: {
            required: false,
            label: 'Product From Local',
         },
      },
   }, {
      name: null,
      lifetime: null,
      feature_image: null,
      price: null,
      clean_after: 0,
      pop3: false,
      imap: false,
      visible: false,
      is_local: false,
   });

   const getExtraFields = computed(() => _.map(_.cloneDeep(props.extraFields), (item, key) => {
      const { attribute, ...rest } = item;

      return {
         ...rest,
         attrs: attribute,
         key,
      };
   }));

   const handleSubmitForm: Form.DynamicForm.HandleSubmitForm = (form, values, resetForm) => {
      return form.post(useRoute('admin.service.store'), {
         forceFormData: true,
         onSuccess: () => {
            resetForm();
         },
      });
   };

   watchEffect(() => {
      _.forEach(getExtraFields.value, item => setField(`extras.${item.key}`, {
         ...item,
         resolveKey: `extras[${item.key}]`,
      }));
   });
</script>
