<template>
   <Head>
      <title>Register</title>
   </Head>
   <!-- Basic registration form-->
   <div class='card shadow-lg border-0 rounded-lg mt-5'>
      <div class='card-header justify-content-center'><h3 class='fw-light my-4'>Create Account</h3></div>
      <div class='card-body'>
         <!-- Registration form-->
         <form @submit.prevent='() => onSubmitForm()'>
            <div class='mb-3'>
               <TheInputField v-model='form.name'
                              :error='form.errors.name'
                              type='text'
                              label='Full Name'
                              placeholder='Enter full name' />
            </div>

            <div class='mb-3'>
               <TheInputField v-model='form.username'
                              :error='form.errors.username'
                              type='text'
                              label='Username'
                              placeholder='Enter username' />
            </div>

            <div class='mb-3'>
               <TheInputField v-model='form.email'
                              :error='form.errors.email'
                              type='email'
                              label='Email'
                              placeholder='Enter email address' />
            </div>

            <div class='row gx-3'>
               <div class='col-md-6'>
                  <div class='mb-3'>
                     <TheInputField v-model='form.password'
                                    :error='form.errors.password'
                                    type='password'
                                    label='Password'
                                    placeholder='Enter password' />
                  </div>
               </div>
               <div class='col-md-6'>
                  <div class='mb-3'>
                     <TheInputField v-model='form.password_confirmation'
                                    :error='form.errors.password_confirmation'
                                    type='password'
                                    label='Confirm Password'
                                    placeholder='Enter confirm password' />
                  </div>
               </div>
            </div>

            <div class='d-flex justify-content-center'>
               <button type='submit' class='btn btn-primary btn-block' :disabled='form.processing'>Register</button>
            </div>
         </form>
      </div>
      <div class='card-footer text-center'>
         <div class='small'>
            <Link :href='$route("login")'>
               Have an account? Go to login
            </Link>
         </div>
      </div>
   </div>
</template>

<script lang='ts'>
   import { defineComponent } from 'vue';
   import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

   import { useRoute } from '~/utils';

   import AuthLayout from '~/layouts/AuthLayout';
   import TheInputField from '~/components/Form/TheInputField';

   export default defineComponent({
      layout: (h, page) => h(AuthLayout, () => page),
      components: {
         TheInputField,
         Head,
         Link,
      },
      setup() {
         const form = useForm({
            name: null,
            username: null,
            email: null,
            password: null,
            password_confirmation: null,
         });

         const onSubmitForm = () => {
            return form.post(useRoute('register'), {
               preserveScroll: false,
               onSuccess: () => {
                  location.reload();
               },
            });
         };

         return { form, onSubmitForm };
      },
   });
</script>
