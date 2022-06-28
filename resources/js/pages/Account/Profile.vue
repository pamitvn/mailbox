<template>
   <!-- Account details card-->
   <div class='card mb-4'>
      <div class='card-header'>Account Details</div>
      <div class='card-body'>
         <form @submit.prevent='() => onSubmitForm()'>
            <div class='mb-3'>
               <label class='small mb-1' for='full_name'>Full Name</label>
               <input v-model='form.name'
                      class='form-control'
                      :class='{"is-invalid": form.errors.name}'
                      id='full_name'
                      type='text'
                      placeholder='Enter full name'>
               <div v-if='form.errors.name' class='invalid-feedback'>
                  {{ form.errors.name }}
               </div>
            </div>

            <div class='mb-3'>
               <label class='small mb-1' for='username'>Username</label>
               <input v-model='form.username'
                      class='form-control'
                      :class='{"is-invalid": form.errors.username}'
                      id='username'
                      type='text'
                      placeholder='Enter username'
                      disabled
               >
               <div v-if='form.errors.username' class='invalid-feedback'>
                  {{ form.errors.username }}
               </div>
            </div>

            <div class='mb-3'>
               <label class='small mb-1' for='inputEmailAddress'>Email</label>
               <input v-model='form.email'
                      class='form-control'
                      :class='{"is-invalid": form.errors.email}'
                      id='inputEmailAddress'
                      type='email'
                      aria-describedby='emailHelp'
                      placeholder='Enter email address'
               >
               <div v-if='form.errors.email' class='invalid-feedback'>
                  {{ form.errors.email }}
               </div>
            </div>

            <div class='mb-3'>
               <label class='small mb-1' for='balance'>Balance</label>
               <input v-model='form.balance'
                      class='form-control'
                      id='balance'
                      type='text'
                      disabled
               >
            </div>

            <!-- Save changes button-->
            <button class='btn btn-primary' type='submit'>Save changes</button>
         </form>
      </div>
   </div>
</template>

<script lang='ts'>
   import { defineComponent } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';
   import { Models } from '~/types';

   import MasterLayout from '~/layouts/MasterLayout.vue';
   import AccountLayout from '~/layouts/AccountLayout.vue';

   export default defineComponent({
      layout: (h, page) => h(MasterLayout, () => h(AccountLayout, () => page)),
      props: {
         user: Models.User,
      },
      setup(props) {
         const form = useForm({
            name: props.user.name,
            username: props.user.username,
            email: props.user.email,
            balance: props.user.balance,
         });

         const onSubmitForm = () => {
            return form.put(useRoute('account.profile'));
         };

         return { form, onSubmitForm };
      },
   });
</script>
