<template>
   <the-head>
      <title>Reset Password Account</title>
   </the-head>
   <!-- Account details card-->
   <div class='card mb-4'>
      <div class='card-header'>Account Details</div>
      <div class='card-body'>
         <form @submit.prevent='() => onSubmitForm()'>
            <div class='mb-3'>
               <label class='small mb-1' for='current_password'>Current Password</label>
               <input v-model='form.current_password'
                      class='form-control'
                      :class='{"is-invalid": form.errors.current_password}'
                      id='current_password'
                      type='password'
                      placeholder='Enter full name'>
               <div v-if='form.errors.current_password' class='invalid-feedback'>
                  {{ form.errors.current_password }}
               </div>
            </div>

            <div class='mb-3'>
               <label class='small mb-1' for='password'>Password</label>
               <input v-model='form.password'
                      class='form-control'
                      :class='{"is-invalid": form.errors.password}'
                      id='password'
                      type='password'
                      placeholder='Enter full name'>
               <div v-if='form.errors.password' class='invalid-feedback'>
                  {{ form.errors.password }}
               </div>
            </div>

            <div class='mb-3'>
               <label class='small mb-1' for='password_confirmation'>Confirm Password</label>
               <input v-model='form.password_confirmation'
                      class='form-control'
                      :class='{"is-invalid": form.errors.password_confirmation}'
                      id='password_confirmation'
                      type='password'
                      aria-describedby='emailHelp'
                      placeholder='Enter email address'
               >
               <div v-if='form.errors.password_confirmation' class='invalid-feedback'>
                  {{ form.errors.password_confirmation }}
               </div>
            </div>

            <!-- Save changes button-->
            <button class='btn btn-primary' type='submit'>Change Password</button>
         </form>
      </div>
   </div>
</template>

<script lang='ts'>
   import { defineComponent } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';

   import MasterLayout from '~/layouts/MasterLayout.vue';
   import AccountLayout from '~/layouts/AccountLayout.vue';

   export default defineComponent({
      layout: (h, page) => h(MasterLayout, () => h(AccountLayout, () => page)),
      setup(props) {
         const form = useForm({
            current_password: null,
            password: null,
            password_confirmation: null,
         });

         const onSubmitForm = () => {
            return form.put(useRoute('account.reset-password'), {
               onSuccess: () => {
                  form.reset();
               },
            });
         };

         return { form, onSubmitForm };
      },
   });
</script>
