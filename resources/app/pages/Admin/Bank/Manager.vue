<template>
   <the-head>
      <title>Bank Managers</title>
   </the-head>
   <crud-layout title='Bank Managers' has-search>
      <template #header-action>
         <the-link :href='$route("admin.bank.create")'>
            <v-button outline>
               Add
            </v-button>
         </the-link>
      </template>
      <v-crud-table
         :columns='columns'
         :data='paginationData'
         title='All banks are available here.'
      >
         <template #row-image='{row,value}'>
            <td class='table--col'>
               <div class='font-medium flex justify-center'>
                  <img v-if='value' class='w-6' :src='`/storage/${value}`' :alt='row.name'>
               </div>
            </td>
         </template>
         <template #row-action='{row}'>
            <td class='table--col'>
               <the-link :href='$route("admin.bank.edit", row.id)' class='ml-2'>
                  <v-button size='xs' variant='info' outline only-icon>
                     <template #icon>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                             fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='w-full h-full'>
                           <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                           <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                        </svg>
                     </template>
                  </v-button>
               </the-link>
               <the-link
                  :href='$route("admin.bank.destroy", row.id)'
                  method='delete'
                  as='button'
                  class='ml-2'
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

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';

   const props = defineProps<{
      paginationData: Utils.Pagination.Cursor<Models.Bank>
   }>();

   const { search, perPage, columns } = usePagination([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'name',
         label: 'Name',
      },
      {
         path: 'accountName',
         label: 'Account Name',
      },
      {
         path: 'accountId',
         label: 'Account Id',
      },
      {
         path: 'image',
         label: 'Image',

      },
      {
         path: 'created_at',
         label: 'Created At',
         display: (row, path, lodash) => dateFormat(lodash.get(row, path, '') as string, 'mmmm dS, yyyy, h:MM:ss TT'),
      },
      {
         path: 'action',
         label: '',
      },
   ]);
</script>
