<template>
   <teleport to='body'>
      <div ref='modalRef'
           class='modal fade'
           :id='modalId'
           tabindex='-1'
           role='dialog'
           aria-hidden='true'
           data-bs-backdrop='static'
           data-bs-keyboard='false'
      >
         <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
               <div class='modal-header'>
                  <h5 class='modal-title'>Enter The Quantity Stock</h5>
                  <button class='btn-close' type='button' data-bs-dismiss='modal' aria-label='Close'></button>
               </div>

               <form @submit.prevent='() => onSubmitForm()'>
                  <div class='modal-body'>
                     <div class='mb-3'>
                        <div class='d-flex justify-content-between mt-2'>
                           <span><b>Product</b></span>
                           <span class='text-red'><b>{{ props.service?.name || 'Unknown' }}</b></span>
                        </div>
                        <div class='d-flex justify-content-between mt-2'>
                           <span><b>Price</b></span>
                           <span class='text-red'><b>{{ priceStatistics.price }}</b></span>
                        </div>
                        <div class='d-flex justify-content-between mt-2'>
                           <span><b>Amount</b></span>
                           <span class='text-red'><b>{{ priceStatistics.amount }}</b></span>
                        </div>
                     </div>

                     <div class='mb-3'>
                        <the-input-field v-model='form.quantity'
                                         :error='form.errors.quantity'
                                         type='number'
                                         label='Quantity'
                                         min='1'
                        />
                     </div>
                  </div>
                  <div class='modal-footer d-flex justify-content-center'>
                     <button class='btn btn-primary' type='submit'>Buy Now</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </teleport>
</template>

<script setup lang='ts'>
   import { computed, onMounted, onUnmounted, ref, watch, watchEffect } from 'vue';
   import { Models } from '~/types';
   import Modal from '~/types/Components/Modal';
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';

   const props = defineProps<{
      service: Models.Service | null
   }>();
   const emit = defineEmits(['update:service']);

   const modalRef = ref<HTMLElement | null>(null);
   const modalBootstrap = ref<Modal.Bootstrap | null>(null);

   const modalId = computed(() => 'buy-product-modal');
   const priceStatistics = computed(() => {
      const price = props.service?.price || 0;
      return {
         price,
         amount: form.quantity * price,
      };
   });

   const form = useForm({
      quantity: 1,
   });

   const forgetModel = () => {
      if (!modalBootstrap.value.hide) return;

      modalBootstrap.value?.hide();
      emit('update:service', null);
   };
   const onSubmitForm = () => {
      return form.transform((data) => ({ ...data, service: props.service.id }))
         .post(useRoute('product.buy'), {
            onSuccess: () => forgetModel(),
         });
   };

   watchEffect(() => {
      props.service
         ? modalBootstrap.value?.show()
         : modalBootstrap.value?.hide();
   });

   watch(modalRef, () => {
      if (!modalRef.value) return;

      // @ts-ignore
      modalBootstrap.value = bootstrap.Modal.getOrCreateInstance(modalRef.value);
   });

   onMounted(() => {
      // @ts-ignore
      modalBootstrap.value = bootstrap.Modal.getOrCreateInstance(modalRef.value);

      modalRef.value.addEventListener('hidden.bs.modal', () => forgetModel());

      onUnmounted(() => {
         forgetModel();
      });
   });

</script>
