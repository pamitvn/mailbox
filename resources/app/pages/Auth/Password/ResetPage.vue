<template>
   <the-head>
      <title>
         Reset your Password
      </title>
   </the-head>
   <div class='card w-full'>
      <h1 class='text-xl md:text-3xl md:text-slate-800 font-bold mb-6'>Reset your Password! ✨</h1>

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
                        disabled
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

   const props = defineProps<{
      token: string
      email: string
   }>();

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
      })).post(useRoute('password.update'), {
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
