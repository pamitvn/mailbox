<template>
   <Head>
      <title>Rest Password</title>
   </Head>
   <!-- Basic login form-->
   <div class='card shadow-lg border-0 rounded-lg mt-5'>
      <div class='card-header justify-content-center'>
         <h3 class='fw-light my-4'>
            Rest Password
         </h3>
      </div>
      <div class='card-body'>
         <div v-if='$page.props.flash.status' class='alert alert-success'>
            {{ $page.props.flash.status }}
         </div>
         <!-- Login form-->
         <form @submit.prevent='() => onSubmitForm()'>
            <!-- Form Group (email address)-->
            <div class='mb-3'>
               <the-input-field v-model='form.email'
                                :error='form.errors.email'
                                type='email'
                                label='Email'
                                placeholder='Enter email address'
                                disabled />
            </div>


            <div class='mb-3'>
               <the-input-field v-model='form.password'
                                :error='form.errors.password'
                                type='password'
                                label='Password'
                                placeholder='Enter password' />
            </div>

            <div class='mb-3'>
               <the-input-field v-model='form.password_confirmation'
                                :error='form.errors.password_confirmation'
                                type='password'
                                label='Confirm Password'
                                placeholder='Confirm password' />
            </div>


            <div class='d-flex align-items-center justify-content-center mt-4 mb-0'>
               <button type='submit' class='btn btn-primary' :disabled='form.processing'>Send Password Reset Link
               </button>
            </div>
         </form>
      </div>
      <div class='card-footer text-center'>
         <div class='small'>
            <the-link :href='$route("login")'>Back to login</the-link>
         </div>
      </div>
   </div>
</template>

<script lang='ts'>
   import { defineComponent } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';

   import AuthLayout from '~/layouts/AuthLayout';
   import { useRoute } from '~/utils';

   export default defineComponent({
      layout: (h, page) => h(AuthLayout, () => page),
      props: {
         token: String,
         email: String,
      },
      setup(props) {
         const form = useForm({
            token: props.token,
            email: props.email,
            password: null,
            password_confirmation: null,
         });

         const onSubmitForm = () => {
            return form.transform((data) => ({
               ...data,
               token: props.token,
               email: props.email,
            })).post(useRoute('password.update'));
         };


         return { form, onSubmitForm };
      },
   });
</script>
