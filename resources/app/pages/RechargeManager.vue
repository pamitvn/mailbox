<template>
   <the-head>
      <title>Deposit</title>
   </the-head>
   <crud-layout title='Deposit'>
      <template #header-action>
         <v-button outline @click='show'>
            Send Deposit
         </v-button>
      </template>
      <v-crud-table
         :columns='columns'
         :data='paginationData'
         title='Every transaction history'
      >
         <template #row-before_transaction='{value}'>
            <td class='table--col'>
               <div class='font-medium text-emerald-500'>
                  {{ numberFormat(value) }}
               </div>
            </td>
         </template>
         <template #row-after_transaction='{value}'>
            <td class='table--col'>
               <div class='font-medium text-rose-500'>
                  {{ numberFormat(value) }}
               </div>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>
   <teleport to='body'>
      <v-modal v-model:open='open' title='Deposit details'>
         <send-deposit-modal-content
            :banks='banks'
            :transfer-content='transferContent'
         />
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import dateFormat from 'dateformat';

   import { useModal, usePagination } from '~/uses';
   import {numberFormat} from '~/utils';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import VModal from '~/components/VModal.vue';
   import SendDepositModalContent from '~/partials/recharge/SendDepositModalContent.vue';

   const props = defineProps<{
      transferContent: string
      banks?: Models.Bank[]
      paginationData: Utils.Pagination.Cursor<Models.Order>
   }>();

   const { search, perPage, columns } = usePagination([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'bank.name',
         label: 'Bank',
      },
      {
         path: 'amount',
         label: 'Amount',
      },
      {
         path: 'before_transaction',
         label: 'Before Transaction',
      },
      {
         path: 'after_transaction',
         label: 'After Transaction',
      },
      {
         path: 'note',
         label: 'Note',
      },
      {
         path: 'created_at',
         label: 'Created At',
         display: (row, path, lodash) => dateFormat(lodash.get(row, path, '') as string, 'mmmm dS, yyyy, h:MM:ss TT'),
      },
   ]);
   const { open, show } = useModal();
</script>
