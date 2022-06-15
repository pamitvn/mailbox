<template>
   <Layout>
      <the-head>
         <title>Manager Balance</title>
      </the-head>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.user.index")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-arrow-left me-1'>
               <line x1='19' y1='12' x2='5' y2='12'></line>
               <polyline points='12 19 5 12 12 5'></polyline>
            </svg>
            Back to Users List
         </the-link>
      </template>
      <div class='card'>
         <div class='card-header'>Balance Details</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='mb-3'>
                  <label class='small mb-1' for='action'>Bank</label>
                  <select v-model='form.bank'
                          id='action'
                          class='form-control'
                          :class='{"is-invalid": form.errors.bank }'
                  >
                     <option :value='null'>Select action</option>
                     <option v-for='(bank , key) in banks' :key='key' :value='bank.id'>{{ bank.name }}</option>
                  </select>

                  <div v-if='form.errors.bank' class='invalid-feedback'>
                     {{ form.errors.bank }}
                  </div>
               </div>

               <div class='mb-3'>
                  <label class='small mb-1' for='action'>Action</label>
                  <select v-model='form.action'
                          id='action'
                          class='form-control'
                          :class='{"is-invalid": form.errors.action }'
                  >
                     <option value=''>Select action</option>
                     <option value='+'>Increase</option>
                     <option value='-'>Decrease</option>
                  </select>

                  <div v-if='form.errors.action' class='invalid-feedback'>
                     {{ form.errors.action }}
                  </div>
               </div>
               <div class='mb-3'>
                  <TheInputField v-model='form.amount'
                                 :error='form.errors.amount'
                                 label='Amount'
                                 type='number'
                                 placeholder='Enter amount'
                  />
               </div>

               <!-- Submit button-->
               <button class='btn btn-primary' type='submit'>Add user</button>
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
      user: Models.User,
      banks: Models.Bank[]
   }>();

   const form = useForm({
      action: '+',
      amount: null,
      bank: null,
   });

   const onSubmitForm = () => {
      return form.post(useRoute('admin.user.balance', props.user.id), {
         onSuccess: () => {
            form.reset();
         },
      });
   };

</script>
