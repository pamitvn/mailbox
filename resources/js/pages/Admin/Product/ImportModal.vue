<template>
   <the-link class='btn btn-sm btn-light text-primary'
             data-bs-toggle='modal'
             :data-bs-target='`#${modalId}`'
   >
      <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
           stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
           class='feather feather-plus'>
         <line x1='12' y1='5' x2='12' y2='19'></line>
         <line x1='5' y1='12' x2='19' y2='12'></line>
      </svg>
      Import
   </the-link>

   <teleport to='body'>
      <div ref='modalRef'
           class='modal fade'
           :id='modalId'
           tabindex='-1'
           role='dialog'
           :aria-labelledby='`${modalId}-label`'
           aria-hidden='true'
           data-bs-backdrop='static'
           data-bs-keyboard='false'
      >
         <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
               <div class='modal-header'>
                  <h5 class='modal-title'>Import Products</h5>
                  <button class='btn-close' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
               </div>

               <form @submit.prevent='() => onSubmitForm()'>
                  <div class='modal-body'>
                     <div class='mb-3'>
                        <the-textarea-field v-model='products'
                                            :error='error'
                                            label='Products'
                                            placeholder='<mail>|<password>'
                        >
                           <template #label='{label, id}'>
                              <label :for='id' class='small mb-1 w-100'>
                                 <div class='d-flex justify-content-between '>
                                    <div class='flex-fill'>
                                       <span>{{ label }}</span>
                                    </div>
                                    <div>
                                 <span v-if='transformData.valid.length' class='text-end me-2'>
                                    <span class='text-success'>[{{ transformData.valid.length }}]</span>
                                    Valid
                                 </span>
                                       <span v-if='transformData.all.length' class='text-end'>
                                    <span class='text-red'>
                                       [{{ transformData.all.length - transformData.valid.length }}]
                                    </span>
                                    Invalid
                                 </span>
                                    </div>
                                 </div>
                              </label>
                           </template>
                        </the-textarea-field>
                     </div>
                     <div class='mb-3'>
                        <the-input-field
                           :error='form.errors.file'
                           label='Import from file (Options, Recommend for large products)'
                           type='file'
                           accept='.txt'
                           placeholder='Only allow txt file'
                           @input='form.file = $event.target.files[0]'
                        />
                     </div>
                  </div>
                  <div class='modal-footer'>
                     <button class='btn btn-secondary' type='button' data-bs-dismiss='modal'>Close</button>
                     <button class='btn btn-primary' type='submit' :disabled='isDisabled'>Import</button>
                  </div>
               </form>

            </div>
         </div>
      </div>
   </teleport>

</template>

<script setup lang='ts'>
   import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
   import { useForm, usePage } from '@inertiajs/inertia-vue3';
   import { refDebounced } from '@vueuse/core';
   import { randomString, useRoute } from '~/utils';
   import Model from '~/types/Components/Modal';

   const form = useForm({
      products: '',
      file: null,
   });

   const modalRef = ref<HTMLElement | null>(null);
   const modalBootstrap = ref<Model.Bootstrap | null>(null);
   const products = ref(form.products);
   const productsDebounced = refDebounced(products, 300);

   const modalId = computed(() => randomString(10));
   const transformData = computed(() => {
      const values = productsDebounced.value.trim();
      const list = _.compact(values.split('\n'));
      let results = [];

      _.forEach(list, item => {
         const [email, password, recovery_mail] = _.split(item, '|');
         const regexEmail = /^([\w_\.\-\+])+\@([\w\-]+\.)+([\w]{2,10})+$/;

         if (!email || !password || !regexEmail.test(email)) return;
         if (recovery_mail && !regexEmail.test(recovery_mail)) return;

         results.push({
            email,
            password,
            recovery_mail,
         });
      });

      results = _.unionBy(results, 'email');

      return {
         valid: results,
         all: list,
      };
   });
   const isDisabled = computed(() => !form.file && !transformData.value.valid.length);
   const error = computed(() => {
      const errors = _.get(usePage().props.value, 'errors', {});
      return errors[_.head(_.keys(errors))];
   });

   const forgetModel = () => {
      if (!modalBootstrap.value.hide) return;

      modalBootstrap.value.hide();
   };

   const onSubmitForm = () => {
      return form.transform((data) => ({
         ...(data.file ? {
            file: data.file,
         } : {
            products: transformData.value.valid,
         }),
      })).post(useRoute('admin.service.product.store'), {
         onSuccess: () => {
            forgetModel();
         },
      });
   };

   watch(modalRef, () => {
      if (!modalRef.value) return;

      // @ts-ignore
      modalBootstrap.value = bootstrap.Modal.getOrCreateInstance(modalRef.value);
   });

   onMounted(() => {
      // @ts-ignore
      modalBootstrap.value = bootstrap.Modal.getOrCreateInstance(modalRef.value);
   });

   onUnmounted(() => {
      forgetModel();
   });

</script>
