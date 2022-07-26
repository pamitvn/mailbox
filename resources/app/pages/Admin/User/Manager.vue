<template>
   <the-head>
      <title>User Managers</title>
   </the-head>
   <crud-layout title='User Managers' has-search>
      <template #header-action>
         <the-link :href='$route("admin.user.create")'>
            <v-button outline>
               Add
            </v-button>
         </the-link>
      </template>

      <dl class='mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 mb-6'>
         <v-simple-stat-card v-for='item in stats' :key='item.id'
                             :label='item.name'
                             :stat='item.stat'>
            <template #icon>
               <font-awesome-icon :icon='item.icon' class='h-6 w-6 text-white' aria-hidden='true' />
            </template>
         </v-simple-stat-card>
      </dl>

      <v-crud-table
         :columns='columns'
         :data='paginationData'
         title='All users are available here.'
      >
         <template #row-action='{row}'>
            <td class='table--col'>
               <the-link :href='$route("admin.user.balance", row.id)'>
                  <v-button size='xs' variant='warning' outline only-icon>
                     <template #icon>
                        <svg class='w-full h-full' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' width='24'
                             height='24'>
                           <g fill='none' class='nc-icon-wrapper'>
                              <path
                                 d='M11.5 17.1c-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79z'
                                 fill='currentColor'></path>
                           </g>
                        </svg>
                     </template>
                  </v-button>
               </the-link>
               <the-link :href='$route("admin.user.edit", row.id)' class='ml-2'>
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
                  :href='$route("admin.user.destroy", row.id)'
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
   import { usePagination } from '~/uses';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import dateFormat from 'dateformat';
   import { numberFormat } from '~/utils';
   import VSimpleStatCard from '~/components/VSimpleStatCard.vue';

   const props = defineProps<{
      paginationData: Utils.Pagination.Type<Models.User>;
      statistics: {
         total: number;
         totalBalance: number
      }
   }>();

   const stats = [
      { id: 1, name: 'Total User', stat: props.statistics?.total, icon: 'fa-solid fa-users' },
      { id: 2, name: 'Total Balance', stat: props.statistics?.totalBalance, icon: 'fa-solid fa-money-check-dollar' },
   ];

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
         path: 'username',
         label: 'Username',

      },
      {
         path: 'email',
         label: 'Email',
      },
      {
         path: 'is_admin',
         label: 'Role',
         display: (row, path, lodash) => lodash.get(row, path, false) ? 'Admin' : 'User',
      },

      {
         path: 'wallet.balance',
         label: 'Balance',
         display: (row, path, lodash) => numberFormat(lodash.get(row, path, 0)),
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
