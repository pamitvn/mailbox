<template>
   <Layout>
      <the-head>
         <title>Edit Bank Detail #{{ bank.id }}</title>
      </the-head>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.bank.index")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-arrow-left me-1'>
               <line x1='19' y1='12' x2='5' y2='12'></line>
               <polyline points='12 19 5 12 12 5'></polyline>
            </svg>
            Back to Bank List
         </the-link>
      </template>
      <div class='card'>
         <div class='card-header'>Bank Details <span class='badge bg-primary'>#{{ bank.id }}</span></div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='mb-3'>
                  <TheInputField v-model='form.name'
                                 :error='form.errors.name'
                                 label='Bank name'
                                 type='text'
                                 placeholder='Enter bank name'
                  />
               </div>

               <div class='mb-3'>
                  <label class='small mb-1' for='image'>Image</label>
                  <input
                     class='form-control'
                     :class='{"is-invalid": form.errors.image }'
                     id='image'
                     type='file'
                     accept='image/*'
                     placeholder='Bank image'
                     @input='form.image = $event.target.files[0]'
                  />

                  <div v-if='form.errors.image' class='invalid-feedback'>
                     {{ form.errors.image }}
                  </div>
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.accountId'
                                 :error='form.errors.accountId'
                                 label='Account ID'
                                 type='text'
                                 placeholder='Enter account id'
                  />
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.accountName'
                                 :error='form.errors.accountName'
                                 label='Account Name'
                                 type='text'
                                 placeholder='Enter account name'
                  />
               </div>

               <!-- Submit button-->
               <button class='btn btn-primary' type='submit'>Update</button>
            </form>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { Models } from '~/types';
   import { useRoute } from '~/utils';

   import Layout from './Layout.vue';
   import TheInputField from '~/components/Form/TheInputField.vue';

   const props = defineProps<{
      bank: Models.Bank
   }>();

   const form = useForm({
      name: props.bank.name ?? null,
      accountId: props.bank.accountId ?? null,
      accountName: props.bank.accountName ?? null,
      image: null,
   });

   const onSubmitForm = () => {
      return form.put(useRoute('admin.bank.update', props.bank.id), {
         onSuccess: () => {
            form.image = null;
         },
      });
   };
</script>
