<template>
   <div class='p-6 mb-3'>
      <div class='grid grid-col gap-2'>
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
            enter-active-class='transition ease-out duration-200 transform'
            enter-from-class='opacity-0 -translate-y-2'
            enter-to-class='opacity-100 translate-y-0'
            leave-active-class='transition ease-out duration-200'
            leave-from-class='opacity-100'
            leave-to-class='opacity-0'
         >
            <div v-if='!!getBankInfo'>
               <div class='grid grid-cols-1 md:grid-cols-2 gap-2 md:gap-6'>
                  <div>
                     <v-input :model-value='getBankInfo?.accountId'
                              type='text'
                              label='Account ID'
                              required
                              disabled
                     />
                  </div>
                  <div>
                     <v-input :model-value='getBankInfo?.accountName'
                              type='text'
                              label='Account Name'
                              required
                              disabled
                     />
                  </div>
               </div>
               <div class='mt-2'>
                  <v-input :model-value='props.transferContent'
                           type='text'
                           label='Content'
                           required
                           disabled
                  />
               </div>
            </div>
         </transition>
      </div>
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

   const bank = ref<string | number | null>(null);

   const getBankInfo = computed<Models.Bank | undefined>(() => {
      const banks = _.cloneDeep(props.banks);

      return _.find(banks, it => (it.id?.toString() || it.id) === (bank.value?.toString() || bank.value)) as Models.Bank;
   });
</script>
