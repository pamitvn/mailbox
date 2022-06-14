<template>
   <Head>
      <title>Forgot Password</title>
   </Head>
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
               <label class='small mb-1' for='email'>Email</label>
               <input v-model='form.email'
                      class='form-control'
                      :class='{"is-invalid": form.errors.email }'
                      id='email'
                      type='email'
                      placeholder='Enter email address'
               />

               <div v-if='form.errors.email' class='invalid-feedback'>
                  {{ form.errors.email }}
               </div>
            </div>

            <div class='d-flex align-items-center justify-content-center mt-4 mb-0'>
               <button type='submit' class='btn btn-primary' :disabled='form.processing'>Send Password Reset Link
               </button>
            </div>
         </form>
      </div>
      <div class='card-footer text-center'>
         <div class='small'>
            <Link :href='$route("login")'>Back to login</Link>
         </div>
      </div>
   </div>
</template>

<script>
   import { defineComponent } from 'vue';
   import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

   import AuthLayout from '~/layouts/AuthLayout';

   export default defineComponent({
      layout: (h, page) => h(AuthLayout, () => page),
      components: {
         Head,
         Link,
      },
      setup() {
         const form = useForm({
            email: null,
         });

         return { form };
      },
   });
</script>
