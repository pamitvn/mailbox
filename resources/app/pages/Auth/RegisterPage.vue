<template>
   <the-head>
      <title>
         Sign Up
      </title>
   </the-head>
   <div class='card w-full'>
      <h1 class='text-xl md:text-3xl md:text-slate-800 font-bold mb-6'>Create your Account! ✨</h1>

      <form @submit.prevent='() => onSubmitForm()'>
         <div class='space-y-4'>
            <div>
               <v-input v-model='form.name'
                        :error='form.errors.name'
                        :required='true'
                        type='text'
                        label='Name'
                        placeholder='Enter your name'
               />
            </div>

            <div>
               <v-input v-model='form.username'
                        :error='form.errors.username'
                        :required='true'
                        type='text'
                        label='Username'
                        placeholder='Enter username'
               />
            </div>

            <div>
               <v-input v-model='form.email'
                        :error='form.errors.email'
                        :required='true'
                        type='email'
                        label='E-Mail'
                        placeholder='Enter E-Mail'
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

            <div>
               <v-input v-model='form.password_confirmation'
                        :error='form.errors.password_confirmation'
                        :required='true'
                        type='password'
                        label='Confirm Password'
                        placeholder='Enter confirm password'
               />
            </div>
         </div>
         <div class='flex items-center justify-center mt-6'>
            <v-button type='submit'
                      size='md'
                      variant='primary'
                      outline
                      :loading='form.processing'
            >
               <template #icon>
                  <font-awesome-icon icon='fa-solid fa-arrow-right-to-bracket' class='w-full h-full' />
               </template>
               Sign Up
            </v-button>
         </div>
      </form>
      <!-- Footer -->
      <div class='pt-5 mt-6 border-t border-slate-200'>
         <div class='text-sm'>
            Have an account?
            <the-link class='font-medium text-indigo-500 hover:text-indigo-600' :href='$route("login")'>Sign In
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
      name: null,
      username: null,
      email: null,
      password: null,
      password_confirmation: null,
   });

   const onSubmitForm = () => {
      return form.post(useRoute('register'), {
         preserveScroll: false,
         onSuccess() {
            location.reload();
         },
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
