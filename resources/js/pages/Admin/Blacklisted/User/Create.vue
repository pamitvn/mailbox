<template>
   <Layout>
      <the-head>
         <title>Create New Blacklisted User</title>
      </the-head>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.blacklisted.user.index")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-arrow-left me-1'>
               <line x1='19' y1='12' x2='5' y2='12'></line>
               <polyline points='12 19 5 12 12 5'></polyline>
            </svg>
            Back to List
         </the-link>
      </template>
      <div class='card'>
         <div class='card-header'>Details</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='mb-3'>
                  <SelectorUser v-model='form.user' label='User' :error='form.errors.user' />
               </div>

               <div class='mb-3'>
                  <the-input-field v-model='form.reason'
                                   :error='form.errors.reason'
                                   label='Reason'
                                   type='text'
                                   placeholder='Enter reason ban'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field v-model='form.duration'
                                   :error='form.errors.duration'
                                   label='User'
                                   type='date'
                                   placeholder='Enter duration ban'
                  />
               </div>


               <!-- Submit button-->
               <button class='btn btn-primary' type='submit'>Add</button>
            </form>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';

   import Layout from './Layout.vue';
   import SelectorUser from '~/components/Form/Selector/SelectorUser.vue';

   const form = useForm({
      user: '',
      reason: '',
      duration: '',
   });

   const onSubmitForm = () => {
      return form.post(useRoute('admin.user.store'), {
         onSuccess: () => {
            form.reset();
         },
      });
   };
</script>
