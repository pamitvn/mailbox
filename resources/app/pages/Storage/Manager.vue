<template>
   <the-head>
      <title>Storage Managers</title>
   </the-head>
   <crud-layout title='Storage Managers' has-search>
      <template #header-action>
         <the-link :href='$route("storage.create")'>
            <v-button variant='primary' outline>
               Add
            </v-button>
         </the-link>
      </template>

      <dl class='mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3 mb-6'>
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
         title='All storages'
         @selected-rows='onSelectedRows'
      >
         <template #row-action='{row}'>
            <td class='table--col'>
               <v-button size='xs' variant='success' outline only-icon
                         @click='exportStorage = row'
               >
                  <template #icon>
                     <svg xmlns='http://www.w3.org/2000/svg' class='w-full h-full' width='24'
                          height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none'
                          stroke-linecap='round' stroke-linejoin='round'>
                        <path stroke='none' d='M0 0h24v24H0z' fill='none'></path>
                        <path d='M14 3v4a1 1 0 0 0 1 1h4'></path>
                        <path d='M11.5 21h-4.5a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v5m-5 6h7m-3 -3l3 3l-3 3'></path>
                     </svg>
                  </template>
               </v-button>
               <the-link :href='$route("storage.container.index", {storage: row.id})' class='ml-2'>
                  <v-button size='xs' variant='warning' outline only-icon>
                     <template #icon>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                             fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='w-full h-full'>
                           <ellipse cx='12' cy='5' rx='9' ry='3'></ellipse>
                           <path d='M21 12c0 1.66-4 3-9 3s-9-1.34-9-3'></path>
                           <path d='M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5'></path>
                        </svg>
                     </template>
                  </v-button>
               </the-link>
               <the-link :href='$route("storage.edit", row.id)' class='ml-2'>
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
                  :href='$route("storage.destroy", row.id)'
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

   <export-storage-data-modal v-model:storage='exportStorage' />
</template>

<script setup lang='ts'>
   import { ref } from 'vue';
   import dateFormat from 'dateformat';

   import { usePagination, useTableCheckbox } from '~/uses';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import VSimpleStatCard from '~/components/VSimpleStatCard.vue';
   import ExportStorageDataModal from '~/partials/storages/ExportStorageDataModal.vue';

   const props = defineProps<{
      statistics: {
         count: number
         live_count: number
         die_count: number
      }
      paginationData: Utils.Pagination.Cursor<Models.Storage>
   }>();

   const stats = [
      { id: 1, name: 'Totals', stat: props.statistics?.count, icon: 'fa-solid fa-diamond' },
      { id: 2, name: 'Live', stat: props.statistics?.live_count, icon: 'fa-solid fa-diamond' },
      { id: 2, name: 'Die', stat: props.statistics?.die_count, icon: 'fa-solid fa-diamond' },
   ];

   const exportStorage = ref<Models.Storage | null>(null);

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
         path: 'containers_count',
         label: 'Count',
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
   const { selected, onSelected: onSelectedRows } = useTableCheckbox();
</script>
