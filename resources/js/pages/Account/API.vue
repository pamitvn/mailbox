<template>
   <the-head>
      <title>Account API</title>
   </the-head>
   <!-- Account details card-->
   <div class='card mb-4'>
      <div class='card-header'>Account Details</div>
      <div class='card-body'>
         <form @submit.prevent='() => onSubmitForm()'>
            <div class='mb-3'>
               <label class='small mb-1'>API Key</label>
               <input v-model='api_key'
                      class='form-control'
                      type='text'
                      disabled
               >
            </div>

            <!-- Save changes button-->
            <button class='btn btn-primary' type='submit'>Create new API</button>
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
      props: ['api_key'],
      setup(props) {
         const form = useForm({});

         const onSubmitForm = () => {
            return form.post(useRoute('account.api'));
         };

         return { form, onSubmitForm };
      },
   });
</script>
