<template>
   <the-head>
      <title>Deposit</title>
   </the-head>
   <crud-layout title='Deposit' has-search>
      <div class='md:grid md:grid-cols-5 md:gap-2'>
         <div class='bg-white rounded-md shadow-xl w-full p-4 border border-gray-200'>
            <send-deposit-content
               :banks='banks'
               :transfer-content='transferContent'
            />
         </div>
         <div class='mt-6 md:mt-0 md:col-span-4'>
            <deposit-history :data='paginationData' />
         </div>
      </div>
   </crud-layout>
</template>

<script setup lang='ts'>
   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import SendDepositContent from '~/partials/recharge/SendDepositContent.vue';
   import DepositHistory from '~/partials/recharge/DepositHistory.vue';

   const props = defineProps<{
      transferContent: string
      banks?: Models.Bank[]
      paginationData: Utils.Pagination.Cursor<Models.Order>
   }>();
</script>
