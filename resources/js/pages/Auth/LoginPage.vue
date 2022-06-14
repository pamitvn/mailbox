<template>
   <Head>
      <title>Login</title>
   </Head>
   <!-- Basic login form-->
   <div class='card shadow-lg border-0 rounded-lg mt-5'>
      <div class='card-header justify-content-center'>
         <h3 class='fw-light my-4'>
            Login
         </h3>
      </div>
      <div class='card-body'>
         <!-- Login form-->
         <form @submit.prevent='() => onSubmitForm()'>
            <!-- Form Group (email address)-->
            <div class='mb-3'>
               <TheInputField v-model='form.username'
                              :error='form.errors.username'
                              type='text'
                              label='Username'
                              placeholder='Enter username'
               />
            </div>
            <!-- Form Group (password)-->
            <div class='mb-3'>
               <TheInputField v-model='form.password'
                              :error='form.errors.password'
                              type='password'
                              label='Password'
                              placeholder='Enter password'
               />
            </div>
            <!-- Form Group (remember password checkbox)-->
            <div class='mb-3'>
               <div class='form-check'>
                  <TheCheckBoxField v-model='form.remember'
                                    :error='form.errors.remember'
                                    label='Remember password' />
               </div>
            </div>
            <!-- Form Group (login box)-->
            <div class='d-flex align-items-center justify-content-between mt-4 mb-0'>
               <Link class='small' :href='$route("password.request")'>Forgot Password?</Link>
               <button type='submit' class='btn btn-primary' :disabled='form.processing'>Login</button>
            </div>
         </form>
      </div>
      <div class='card-footer text-center'>
         <div class='small'>
            <Link :href='$route("register")'>Need an account? Sign up!</Link>
         </div>
      </div>
   </div>
</template>

<script lang='ts'>
   import { defineComponent } from 'vue';
   import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

   import { useRoute } from '~/utils';
   import AuthLayout from '~/layouts/AuthLayout';
   import TheCheckBoxField from '~/components/Form/TheCheckBoxField.vue';
   import TheInputField from '~/components/Form/TheInputField.vue';

   export default defineComponent({
      layout: (h, page) => h(AuthLayout, () => page),
      components: {
         TheInputField,
         TheCheckBoxField,
         Head,
         Link,
      },
      setup() {
         const form = useForm({
            username: null,
            password: null,
            remember: false,
         });

         const onSubmitForm = () => {
            return form.post(useRoute('login'), {
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
