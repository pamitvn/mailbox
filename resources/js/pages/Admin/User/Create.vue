<template>
   <Layout>
      <the-head>
         <title>Create New User</title>
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
         <div class='card-header'>Account Details</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='mb-3'>
                  <TheInputField v-model='form.name'
                                 :error='form.errors.name'
                                 label='Full Name'
                                 type='text'
                                 placeholder='Enter your full name'
                  />
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.username'
                                 :error='form.errors.username'
                                 label='Username'
                                 type='text'
                                 placeholder='Enter your username'
                  />
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.email'
                                 :error='form.errors.email'
                                 label='Email'
                                 type='email'
                                 placeholder='Enter your email address'
                  />
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.balance'
                                 :error='form.errors.balance'
                                 label='Balance'
                                 type='number'
                  />
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.password'
                                 :error='form.errors.password'
                                 label='Password'
                                 type='password'
                                 placeholder='Enter your password'
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
   import { useRoute } from '~/utils';

   import Layout from './Layout.vue';
   import TheInputField from '~/components/Form/TheInputField.vue';

   const form = useForm({
      name: null,
      username: null,
      email: null,
      balance: 0,
      password: null,
   });

   const onSubmitForm = () => {
      return form.post(useRoute('admin.user.store'), {
         onSuccess: () => {
            form.reset();
         },
      });
   };
</script>
