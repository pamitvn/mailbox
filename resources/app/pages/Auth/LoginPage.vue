<template>
   <the-head>
      <title>
         Sign In
      </title>
   </the-head>
   <div class='card w-full'>
      <h1 class='text-3xl md:text-slate-800 font-bold mb-6'>Login Account! ✨</h1>

      <form @submit.prevent='() => onSubmitForm()'>
         <div class='space-y-4'>
            <div>
               <v-input v-model='form.username'
                        :error='form.errors.username'
                        :required='true'
                        type='text'
                        label='Username'
                        placeholder='Enter username'
                        helper='Can using username or email'
               />
            </div>
            <div>
               <v-input v-model='form.password'
                        :error='form.errors.password'
                        :required='true'
                        type='password'
                        label='Password'
                        placeholder='Enter password'
               />
            </div>
         </div>
         <div class='flex items-center justify-between mt-6'>
            <div class='mr-1'>
               <the-link class='text-sm underline hover:no-underline' :href='$route("password.request")'>Forgot
                  Password?
               </the-link>
            </div>
            <v-button type='submit'
                      variant='primary'
                      outline
                      :disabled='form.processing'
            >
               Login
            </v-button>
         </div>
      </form>
      <!-- Footer -->
      <div class='pt-5 mt-6 border-t border-slate-200'>
         <div class='text-sm'>
            Don’t you have an account?
            <the-link class='font-medium text-indigo-500 hover:text-indigo-600' :href='$route("register")'>Sign Up
            </the-link>
         </div>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';
   import VInput from '~/components/Form/VInput.vue';
   import VButton from '~/components/VButton.vue';

   const form = useForm({
      username: '',
      password: '',
   });

   const onSubmitForm = () => {
      return form.post(useRoute('login'), {
         preserveScroll: false,
      });
   };
</script>

<style lang='scss' scoped>
   .card {
      @apply py-6 px-6 rounded-lg bg-white text-black;
   }

   @screen md {
      .card {
         @apply m-0 p-0
      }
   }
</style>
