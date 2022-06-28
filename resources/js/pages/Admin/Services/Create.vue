<template>
   <Layout>
      <the-head>
         <title>Create New Service</title>
      </the-head>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.service.index")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-arrow-left me-1'>
               <line x1='19' y1='12' x2='5' y2='12'></line>
               <polyline points='12 19 5 12 12 5'></polyline>
            </svg>
            Back to list
         </the-link>
      </template>
      <div class='card'>
         <div class='card-header'>Details</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()' enctype='multipart/form-data'>
               <div class='mb-3'>
                  <TheInputField v-model='form.name'
                                 :error='form.errors.name'
                                 label='Name'
                                 type='text'
                                 placeholder='Enter name'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field
                     label='Image'
                     type='file'
                     accept='image/*'
                     placeholder='Bank image'
                     @input='form.feature_image = $event.target.files[0]'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field v-model='form.price'
                                   :error='form.errors.price'
                                   label='Price'
                                   type='number'
                                   placeholder='Enter price'
                  />
               </div>

               <div class='mb-3'>
                  <the-switch-field v-model='form.recovery_mail'
                                    :error='form.errors.recovery_mail'
                                    label='Recovery Mail'
                  />
               </div>

               <div class='mb-3'>
                  <the-switch-field v-model='form.visible'
                                    :error='form.errors.visible'
                                    label='Visible'
                  />
               </div>

               <!-- Submit button-->
               <button class='btn btn-primary' type='submit'>Add</button>
            </form>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';
   import { Models } from '~/types';

   import Layout from './Layout.vue';

   type FormType = Pick<Models.Service, 'name' | 'feature_image' | 'price' | 'recovery_mail' | 'visible'>;

   const form = useForm<FormType>({
      name: null,
      feature_image: null,
      price: 0,
      recovery_mail: false,
      visible: false,
   });

   const onSubmitForm = () => {
      return form.post(useRoute('admin.service.store'), {
         forceFormData: true,
         onSuccess: () => {
            form.reset();
         },
      });
   };

</script>
