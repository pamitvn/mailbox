<template>
   <h2 class='font-semibold text-slate-800'>Deposit details</h2>
   <div class='grid grid-col gap-2 mt-3'>
      <div>
         <v-select v-model='bank'
                   :options='banks'
                   class='w-full'
                   label='Bank'
                   placeholder='Select Bank'
                   path-to-key='id'
                   path-to-label='name'
                   required
         />
      </div>

      <transition
         enter-active-class='transition ease-out duration-300 transform'
         enter-from-class='opacity-0 -translate-y-2'
         enter-to-class='opacity-100 translate-y-0'
         leave-active-class='transition ease-out duration-300'
         leave-from-class='opacity-100'
         leave-to-class='opacity-0'
      >
         <div v-if='!!getBankInfo'>
            <div>
               <v-input :model-value='getBankInfo?.accountId'
                        type='text'
                        label='Account ID'
                        class='text-gray-900'
                        required
                        disabled
               />
            </div>

            <div class='mt-2'>
               <v-input :model-value='getBankInfo?.accountName'
                        type='text'
                        label='Account Name'
                        class='text-gray-900'
                        required
                        disabled
               />
            </div>
            <div class='mt-2'>
               <v-input :model-value='props.transferContent'
                        type='text'
                        label='Content'
                        class='text-gray-900'
                        required
                        disabled
               />
            </div>
         </div>
      </transition>
   </div>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref } from 'vue';

   import type { Models } from '~/types/Models';

   import VSelect from '~/components/Form/VSelect.vue';
   import VInput from '~/components/Form/VInput.vue';

   const props = defineProps<{
      banks: Models.Bank[]
      transferContent: string
   }>();

   const bank = ref<string | number | null>(_.head(props.banks)?.id || null);

   const getBankInfo = computed<Models.Bank | undefined>(() => {
      const banks = _.cloneDeep(props.banks);

      return _.find(banks, it => (it.id?.toString() || it.id) === (bank.value?.toString() || bank.value)) as Models.Bank;
   });
</script>
