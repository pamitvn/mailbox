<template>
   <the-link class='btn btn-sm btn-light text-primary' @click='onShow'>
      <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
           stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
           class='feather feather-plus'>
         <line x1='12' y1='5' x2='12' y2='19'></line>
         <line x1='5' y1='12' x2='19' y2='12'></line>
      </svg>
      Import
   </the-link>

   <the-modal
      @interface='getInterface'
      data-bs-backdrop='static'
      data-bs-keyboard='false'
   >
      <div class='modal-dialog'>
         <div class='modal-content'>
            <div class='modal-header'>
               <h5 class='modal-title'>Import Products</h5>
               <button type='button' class='btn-close' @click='onHide'></button>
            </div>

            <form @submit.prevent='() => onSubmitForm()'>
               <div class='modal-body'>
                  <div class='mb-3'>
                     <the-textarea-field v-model='form.products'
                                         :error='form.errors.products'
                                         label='Products'
                                         placeholder='<mail>|<password>'
                     />
                  </div>
                  <div class='mb-3'>
                     <the-input-field
                        label='Import from file (Options, Recommend for large products)'
                        type='file'
                        accept='.txt'
                        placeholder='Only allow txt file'
                        @input='form.file = $event.target.files[0]'
                     />
                  </div>
               </div>
               <div class='modal-footer'>
                  <button type='button' class='btn btn-secondary' @click='onHide'>Close</button>
                  <button type='submit' class='btn btn-primary'>Import</button>
               </div>
            </form>
         </div>
      </div>
   </the-modal>
</template>

<script setup lang='ts'>
   import { useModal } from '~/uses';
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';
   import TheModal from '~/components/Modal/TheModal.vue';
   import { watchEffect } from 'vue';

   const { getInterface, onShow, onHide } = useModal();
   const form = useForm({
      products: null,
      file: null,
   });

   const onSubmitForm = () => {
      console.log(form);
      return form.transform((data) => ({
         ...(data.file ? {
            file: data.file,
         } : {
            products: data.products,
         }),
      })).post(useRoute('admin.service.product.store'));
   };

   watchEffect(() => {
      console.log(form.products);
   })
</script>
