<template>
   <the-head>
      <title>Manager Product of {{ service.name }}</title>
   </the-head>
   <crud-layout :title='`Manager Product of ${service.name}`'
                has-search
                :filter-fields='filterFields'
   >
      <!--      <template #header-action>-->
      <!--         <v-button outline>-->
      <!--            Import-->
      <!--         </v-button>-->
      <!--      </template>-->
      <template v-if='!!selected.length' #selected-action>
         <div class='flex items-center'>
            <div class='hidden md:block text-sm italic mr-2 whitespace-nowrap'><span>{{ selected.length }}</span> items
               selected
            </div>
            <v-button variant='danger' size='sm' @click='() => bulkDeleter()'>
               Delete
            </v-button>
         </div>
      </template>

      <v-crud-table
         :columns='columns'
         :data='paginationData'
         title='All products are available here.'
         has-checkbox
         @selected-rows='onSelectedRows'
      >
         <template #row-status='{value}'>
            <td class='table--col'>
               <div v-html='statusHtmlLabel[value]'></div>
            </td>
         </template>
         <template #row-in_stock='{value}'>
            <td class='table--col'>
               <v-checked-or-fails :value='value' />
            </td>
         </template>
         <template #row-action='{row}'>
            <td class='table--col'>
               <the-link
                  :href='$route("admin.service.product.destroy", row.id)'
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
   import _ from 'lodash';
   import { reactive, watch } from 'vue';
   import dateFormat from 'dateformat';
   import { Inertia } from '@inertiajs/inertia';

   import { usePagination, usePaginationCUSocket, useTableCheckbox } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';
   import type { Components } from '~/types/Components';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import VCheckedOrFails from '~/components/VCheckedOrFails.vue';

   const props = defineProps<{
      statusHtmlLabel: {
         [key: string]: string
      }
      statusLabel: {
         [key: string]: string
      }

      service: Models.Service
      paginationData: Utils.Pagination.Type<Models.Product>
   }>();

   const filterFields = reactive<Components.Table.FilterCard.Fields>([
      {
         is: 'v-switch',
         name: 'in_stock',
         label: 'In Stock',
      },
      {
         name: 'status',
         label: 'Filter by status',
         placeholder: 'Status',
         options: props.statusLabel,
      },
   ]);

   const { search, perPage, columns } = usePagination([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'payload',
         label: 'Payload',
         display: (row, path, lodash) => lodash.truncate(lodash.get(row, path, '') as string),
      },
      {
         path: 'status',
         label: 'Status',
      },
      {
         path: 'in_stock',
         label: 'In Stock',
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
   const {
      paginationData,
      setPaginationData,
   } = usePaginationCUSocket<Models.Product>(_.cloneDeep(props.paginationData), {
      channel: `user.admin`,
      event: {
         create: `.service.${props.service.id}.product.create`,
         update: `.service.${props.service.id}.product.update`,
      },
   });

   const bulkDeleter = () => {
      if (confirm('Warning! You won\'t be able to undo the deletion once you confirm it. Are you certain?') === false) return;

      const url = useRoute('admin.service.product.bulk-destroy', {
         service: props.service.id,
      });
      const data = { includes: selected };

      return Inertia.post(url, data);
   };

   watch(() => props.paginationData, () => {
      setPaginationData(_.cloneDeep(props.paginationData));
   });
</script>
