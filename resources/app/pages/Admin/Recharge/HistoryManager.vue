<template>
   <the-head>
      <title>Recharge Histories</title>
   </the-head>
   <crud-layout title='Recharge Histories' has-search>
      <v-crud-table
         :columns='columns'
         :data='paginationData'
         title='Every transaction history'
      >
         <template #row-user='{value}'>
            <td class='table--col'>
               <div>
                  <v-user-label :data='value' :gravatar='value.email' has-avatar />
               </div>
            </td>
         </template>
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
         <template #row-action='{row}'>
            <td class='table--col'>
               <the-link
                  :href='$route("admin.recharge-history.destroy", row.id)'
                  method='delete'
                  as='button'
               >
                  <v-button size='xs' variant='danger' outline only-icon>
                     <template #icon>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='w-full h-full'>
                           <polyline points='3 6 5 6 21 6'></polyline>
                           <path
                              d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                           <line x1='10' y1='11' x2='10' y2='17'></line>
                           <line x1='14' y1='11' x2='14' y2='17'></line>
                        </svg>
                     </template>
                  </v-button>
               </the-link>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>
</template>

<script setup lang='ts'>
   import dateFormat from 'dateformat';

   import { usePagination } from '~/uses';
   import { numberFormat } from '~/utils';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import VUserLabel from '~/components/VUserLabel.vue';

   const props = defineProps<{
      paginationData: Utils.Pagination.Type<Models.Blacklisted>;
   }>();

   const { search, perPage, columns } = usePagination([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'user',
         label: 'User',
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
         label: 'Before',
      },
      {
         path: 'after_transaction',
         label: 'After',
      },
      {
         path: 'note',
         label: 'Note',
      },
      {
         path: 'created_at',
         label: 'Created At',
         display: (row, path, lodash) => dateFormat(lodash.get(row, path, '') as string, 'mmmm dS, yyyy'),
      },
      {
         path: 'action',
         label: '',
      },
   ]);
</script>
