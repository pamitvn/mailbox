<template>
   <the-head>
      <title>Forgot Password</title>
   </the-head>
   <!-- Basic login form-->
   <div class='card shadow-lg border-0 rounded-lg mt-5'>
      <div class='card-header justify-content-center'>
         <h3 class='fw-light my-4'>
            Forgot Password
         </h3>
      </div>
      <div class='card-body'>
         <div v-if='$page.props.flash.status' class='alert alert-success'>
            {{ $page.props.flash.status }}
         </div>
         <!-- Login form-->
         <form @submit.prevent='form.post($route("password.email"))'>
            <!-- Form Group (email address)-->
            <div class='mb-3'>
               <the-input-field v-model='form.email'
                                :error='form.errors.email'
                                type='email'
                                label='Email'
                                placeholder='Enter email address'
               />
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

   export default defineComponent({
      layout: (h, page) => h(AuthLayout, () => page),
      setup() {
         const form = useForm({
            email: '',
         });

         return { form };
      },
   });
</script>
