<template>
   <the-head>
      <title>
         Forgot Password
      </title>
   </the-head>
   <div class='card w-full'>
      <h1 class='text-xl md:text-3xl md:text-slate-800 font-bold mb-6'>Forgot Password! ✨</h1>

      <banner type='success' :open='$page.props?.flash?.status' class='mb-4'>
         {{ $page.props?.flash?.status }}
      </banner>

      <form @submit.prevent='() => onSubmitForm()'>
         <div class='space-y-4'>
            <div>
               <v-input v-model='form.email'
                        :error='form.errors.email'
                        :required='true'
                        type='email'
                        label='Email'
                        placeholder='Enter email'
               />
            </div>
         </div>
         <div class='flex items-center justify-end mt-6'>
            <v-button type='submit'
                      variant='primary'
                      outline
                      :disabled='form.processing'
            >
               Send Reset Link
            </v-button>
         </div>
      </form>
   </div>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';
   import VInput from '~/components/Form/VInput.vue';
   import VButton from '~/components/VButton.vue';
   import Banner from '@UI/components/Banner.vue';

   const form = useForm({
      email: '',
   });

   const onSubmitForm = () => {
      return form.post(useRoute('password.email'), {
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
