<template>
   <v-crud-table
      :columns='columns'
      :data='data'
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
</template>

<script setup lang='ts'>
   import dateFormat from 'dateformat';

   import { numberFormat } from '~/utils';
   import { usePagination } from '~/uses';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import VCrudTable from '~/components/CRUD/VCrudTable.vue';

   defineProps<{
      data: Utils.Pagination.Cursor<Models.Order>
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
</script>
