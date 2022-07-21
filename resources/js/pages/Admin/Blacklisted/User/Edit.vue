<template>
   <the-head>
      <title>Edit Blacklisted User #{{ props.data.id }}</title>
   </the-head>
   <Layout>
      <template #header-title>
         Edit Blacklisted User #{{ props.data.id }}
      </template>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.blacklisted.user.index")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-arrow-left me-1'>
               <line x1='19' y1='12' x2='5' y2='12'></line>
               <polyline points='12 19 5 12 12 5'></polyline>
            </svg>
            Back to List
         </the-link>
      </template>
      <div class='card'>
         <div class='card-header'>Details #{{ props.data.id }}</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='mb-3'>
                  <the-input-field v-model='form.reason'
                                   :error='form.errors.reason'
                                   label='Reason'
                                   type='text'
                                   placeholder='Enter reason ban'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field v-model='form.duration'
                                   :error='form.errors.duration'
                                   label='Duration'
                                   type='date'
                                   placeholder='Enter duration ban'
                                   :min='new Date().toISOString().split("T")[0]'
                  />
               </div>

               <!-- Submit button-->
               <button class='btn btn-primary' type='submit'>Save change</button>
            </form>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';

   import Layout from './Layout.vue';
   import { Models } from '~/types';
   import { watchEffect } from 'vue';
   import dateFormat from 'dateformat';

   const props = defineProps<{
      data: Models.Blacklisted
   }>();

   const form = useForm<Pick<Models.Blacklisted, 'reason' | 'duration'>>({
      reason: null,
      duration: null,
   });

   const onSubmitForm = () => {
      return form.put(useRoute('admin.blacklisted.user.update', props.data.id));
   };

   watchEffect(() => {
      const data = props.data;

      if (!data) return;

      form.reason = data.reason;
      form.duration = data.duration ? dateFormat(data.duration ?? '', 'yyyy-mm-dd') : null;
   });
</script>
