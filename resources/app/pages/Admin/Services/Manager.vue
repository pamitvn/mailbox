<template>
   <the-head>
      <title>Service Managers</title>
   </the-head>
   <crud-layout title='Service Managers' has-search>
      <template #header-action>
         <the-link :href='$route("admin.service.create")'>
            <v-button outline>
               Add
            </v-button>
         </the-link>
      </template>
      <v-crud-table
         :columns='columns'
         :data='paginationData'
         title='All services are available here.'
      >
         <template #row-feature_image='{row,value}'>
            <td class='table--col'>
               <div class='font-medium flex justify-center'>
                  <img v-if='value' class='w-6' :src='`/storage/${value}`' :alt='row.name'>
               </div>
            </td>
         </template>
         <template #row-pop3='{value}'>
            <td class='table--col'>
               <div class='font-medium'>
                  <v-checked-or-fails :value='value' />
               </div>
            </td>
         </template>
         <template #row-imap='{value}'>
            <td class='table--col'>
               <div class='font-medium'>
                  <v-checked-or-fails :value='value' />
               </div>
            </td>
         </template>
         <template #row-visible='{value}'>
            <td class='table--col'>
               <div class='font-medium'>
                  <v-checked-or-fails :value='value' />
               </div>
            </td>
         </template>
         <template #row-price='{value}'>
            <td class='table--col'>
               <div class='font-medium text-emerald-500'>
                  {{ numberFormat(value) }}
               </div>
            </td>
         </template>
         <template #row-action='{row}'>
            <td class='table--col'>
               <v-button size='xs' variant='secondary' outline only-icon
                         @click='() => onOpenPermissionModal(row)'
               >
                  <template #icon>
                     <svg xmlns='http://www.w3.org/2000/svg' class='w-full h-full' width='24' height='24'
                          viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round'
                          stroke-linejoin='round'>
                        <path stroke='none' d='M0 0h24v24H0z' fill='none'></path>
                        <circle cx='8' cy='15' r='4'></circle>
                        <line x1='10.85' y1='12.15' x2='19' y2='4'></line>
                        <line x1='18' y1='5' x2='20' y2='7'></line>
                        <line x1='15' y1='8' x2='17' y2='10'></line>
                     </svg>
                  </template>
               </v-button>
               <the-link :href='$route("admin.service.user-purchased", {service: row.id})' class='ml-2'>
                  <v-button size='xs' variant='primary' outline only-icon>
                     <template #icon>
                        <svg xmlns='http://www.w3.org/2000/svg' class='w-full h-full' width='24' height='24'
                             viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none'
                             stroke-linecap='round' stroke-linejoin='round'>
                           <path stroke='none' d='M0 0h24v24H0z' fill='none'></path>
                           <circle cx='6' cy='19' r='2'></circle>
                           <circle cx='17' cy='19' r='2'></circle>
                           <path d='M17 17h-11v-14h-2'></path>
                           <path d='M6 5l14 1l-1 7h-13'></path>
                        </svg>
                     </template>
                  </v-button>
               </the-link>
               <the-link :href='$route("admin.service.product.index", {service: row.id})' class='ml-2'>
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
               <the-link :href='$route("admin.service.edit", row.id)' class='ml-2'>
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
                  :href='$route("admin.service.destroy", row.id)'
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

   <service-permission-modal v-model:service='service' />
</template>

<script setup lang='ts'>
   import { ref } from 'vue';
   import dateFormat from 'dateformat';

   import { usePagination } from '~/uses';
   import { numberFormat } from '~/utils';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import VCheckedOrFails from '~/components/VCheckedOrFails.vue';
   import ServicePermissionModal from '~/partials/service/ServicePermissionModal.vue';

   const props = defineProps<{
      paginationData: Utils.Pagination.Cursor<Models.Service>
   }>();

   const service = ref<Models.Service | null>(null);

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
         path: 'lifetime',
         label: 'Lifetime',
      },
      {
         path: 'feature_image',
         label: 'Feature Image',
      },
      {
         path: 'products_count',
         label: 'Products',
         display: (row, value, lodash) => numberFormat(lodash.get(row, value, '0')),
      },
      {
         path: 'pop3',
         label: 'Pop3',
      },
      {
         path: 'imap',
         label: 'IMAP',
      },
      {
         path: 'visible',
         label: 'Visible',
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

   const onOpenPermissionModal = (item: Models.Service) => {
      service.value = item ?? null;
   };
</script>
