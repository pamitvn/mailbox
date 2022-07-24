<template>
   <crud-layout title='Orders' :filter-fields='filterFields'>
      <template v-if='!!selected.length' #selected-action>
         <div class='flex items-center'>
            <div class='hidden md:block text-sm italic mr-2 whitespace-nowrap'><span>{{ selected.length }}</span> items
               selected
            </div>
            <v-button variant='warning' size='sm' @click='() => bulkExport()'>
               Export
            </v-button>
         </div>
      </template>
      <v-crud-table
         :columns='columns'
         :data='paginationData'
         has-checkbox
         title='All orders'
         @selected-rows='onSelectedRows'
      >
         <template #row-price='{value}'>
            <td class='table--col'>
               <div class='font-medium text-rose-500'>
                  {{ value }}
               </div>
            </td>
         </template>
         <template #row-quantity='{value}'>
            <td class='table--col'>
               <div class='font-medium text-emerald-500'>
                  {{ value }}
               </div>
            </td>
         </template>
         <template #row-action='{row}'>
            <td class='table--col'>
               <v-button size='xs' variant='secondary' outline only-icon @click='() => viewProduct(row)'>
                  <template #icon>
                     <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='feather feather-eye'>
                        <path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path>
                        <circle cx='12' cy='12' r='3'></circle>
                     </svg>
                  </template>
               </v-button>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>

   <order-view-product v-model:order='order' />
</template>

<script setup lang='ts'>
   import { reactive, ref } from 'vue';
   import axios from 'axios';
   import fileSaver from 'file-saver';
   import dateFormat from 'dateformat';

   import { usePagination, useTableCheckbox, useToast } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Utils } from '~/types/Utils';
   import type { Components } from '~/types/Components';
   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import OrderViewProduct from '~/partials/order/OrderViewProduct.vue';

   const props = defineProps<{
      statusHtmlLabel: Components.Table.FilterCard.defineField
      statusLabel: Components.Table.FilterCard.defineField
      services: Components.Table.FilterCard.defineField
      paginationData: Utils.Pagination.Cursor<Models.Order>
   }>();

   const order = ref<Models.Order | null>(null);
   const filterFields = reactive<Components.Table.FilterCard.Fields>([
      {
         name: 'service_id',
         label: 'Filter by Product',
         options: props.services,
      },
   ]);

   const { search, perPage, columns } = usePagination([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'service.name',
         label: 'Product',
      },
      {
         path: 'price',
         label: 'Price',
      },
      {
         path: 'quantity',
         label: 'Quantity',
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

   const bulkExport = async () => {
      const url = useRoute('product.export');
      const data = { includes: selected };

      try {
         const response = await axios.post(url, data, {
            responseType: 'arraybuffer',
            headers: {
               'Accept': 'text/plain',
            },
         });

         const blob = new Blob([response.data], {
            type: 'text/plain;charset=utf-8',
         });

         fileSaver.saveAs(blob, `[${new Date().toISOString()}] MailBox Orders.txt`);
      } catch (e) {
         useToast(`An problem occurred during the download.`, {
            type: 'danger',
         });
      }

   };
   const viewProduct = (row) => {
      order.value = row;
   };

</script>
