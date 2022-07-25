<template>
   <teleport to='body'>
      <v-modal v-model:open='open'
               :has-header='false'
               v-slot='{onClose}'
               max-width='sm'
      >
         <div class='p-6'>
            <div class='relative'>
               <button class='absolute -top-3 -right-3 text-slate-400 hover:text-slate-500' @click='onClose'>
                  <div class='sr-only'>Close</div>
                  <svg class='w-4 h-4 fill-current'>
                     <path
                        d='M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z'></path>
                  </svg>
               </button>
               <h4 class='text-2xl capitalize font-bold font-serif text-indigo-500'>Enter The Quantity Stock</h4>

               <p class='mt-2 text-md text-emerald-600'>Product: <span class='uppercase font-bold'>{{ service?.name
                  }}</span>
               </p>

               <div class='grid gap-3 mt-3'>
                  <div class='buy-product__input--wrapper'>
                     <v-input
                        v-model='quantity'
                        label='Quantify'
                        type='number'
                        BStyle='underline'
                        BSize='large'
                        min='1'
                     />
                  </div>

                  <div class='buy-product__input--wrapper'>
                     <v-input
                        :model-value='priceStatistics.price'
                        label='Price'
                        type='number'
                        BStyle='underline'
                        BSize='large'
                        disabled
                     />
                  </div>

                  <div class='buy-product__input--wrapper'>
                     <v-input
                        :model-value='priceStatistics.amount'
                        label='Amount'
                        type='number'
                        BStyle='underline'
                        BSize='large'
                        disabled
                     />
                  </div>
               </div>

               <div class='flex justify-center items-center mt-6'>
                  <v-button size='md' block outline @click='() => onSubmitForm()'>
                     Buy now!
                  </v-button>
               </div>
            </div>
         </div>
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, watch } from 'vue';
   import { Inertia } from '@inertiajs/inertia';
   import { usePage } from '@inertiajs/inertia-vue3';

   import { useModal, useToast } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Models } from '~/types/Models';

   import VModal from '~/components/VModal.vue';
   import VInput from '~/components/Form/VInput.vue';
   import VButton from '~/components/VButton.vue';

   const props = defineProps<{
      service: Models.Service | null
   }>();
   const emit = defineEmits(['update:service']);

   const quantity = ref(1);

   const priceStatistics = computed(() => {
      const price = props.service?.price || 0;
      return {
         price,
         amount: quantity.value * price,
      };
   });

   const { show, open, close } = useModal({
      onClose: () => {
         emit('update:service', null);
      },
   });

   const onSubmitForm = () => {
      return Inertia.post(useRoute('product.buy'), {
         quantity: quantity.value,
         service: props.service?.id,
      }, {
         onSuccess: () => {
            close();
         },
         onError() {
            const errors = usePage().props.value?.errors || [];

            _.forEach(_.keys(errors), key => {
               const message = _.get(errors, key as string);

               if (!message) return;

               useToast(message, {
                  type: 'danger',
               });
            });
         },
      });
   };

   watch(() => props.service, (val) => {
      val
         ? show()
         : close();
   });
</script>

<style lang='scss'>
   .buy-product__input--wrapper {
      @apply flex items-center justify-center border-0 border-b border-gray-300;

      label {
         @apply flex items-center justify-center;

         width: 50% !important;
      }

      input {
         @apply text-end;

         width: 50% !important;
         border: 0 !important;

         &[disabled] {
            background-color: unset !important;
            color: black !important;
         }
      }
   }
</style>
