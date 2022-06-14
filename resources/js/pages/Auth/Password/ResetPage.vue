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
               <label class='small mb-1' for='email'>Email</label>
               <input v-model='form.email'
                      class='form-control'
                      :class='{"is-invalid": form.errors.email }'
                      id='email'
                      type='email'
                      placeholder='Enter email address'
                      disabled
               />

               <div v-if='form.errors.email' class='invalid-feedback'>
                  {{ form.errors.email }}
               </div>
            </div>


            <div class='mb-3'>
               <label class='small mb-1' for='inputPassword'>Password</label>
               <input v-model='form.password'
                      class='form-control'
                      :class='{"is-invalid": form.errors.password}'
                      id='inputPassword'
                      type='password'
                      placeholder='Enter password'>
               <div v-if='form.errors.password' class='invalid-feedback'>
                  {{ form.errors.password }}
               </div>
            </div>

            <div class='mb-3'>
               <label class='small mb-1' for='inputConfirmPassword'>Confirm Password</label>
               <input v-model='form.password_confirmation'
                      class='form-control'
                      :class='{"is-invalid": form.errors.password_confirmation}'
                      id='inputConfirmPassword'
                      type='password'
                      placeholder='Confirm password'>
               <div v-if='form.errors.password_confirmation' class='invalid-feedback'>
                  {{ form.errors.password_confirmation }}
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
   import { defineComponent, watch, watchEffect } from 'vue';
   import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

   import AuthLayout from '~/layouts/AuthLayout';
   import { useRoute } from '~/utils';

   export default defineComponent({
      layout: (h, page) => h(AuthLayout, () => page),
      components: {
         Head,
         Link,
      },
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
