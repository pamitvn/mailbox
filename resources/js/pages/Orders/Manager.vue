<template>
   <the-head>
      <title>Orders</title>
   </the-head>
   <Layout>
      <the-filter-table-card :fields='filterFields' />
      <div class='card'>
         <div class='card-body'>
            <TheTable v-model:search='search'
                      v-model:per-page='perPage'
                      :has-checkbox='true'
                      :data='paginationData'
                      :columns='columns'
                      @selected-rows='onSelectedRows'
            >
               <template #header-actions>
                  <button class='btn btn-success btn-xs' @click='() => bulkExport()'>
                     <i class='fa-solid fa-trash-can me-1'></i>
                     Export (Selected rows)
                  </button>
               </template>
               <template #row-action='{row}'>
                  <td>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               data-bs-toggle='modal'
                               data-bs-target='#view-product-model'
                               @click='() => viewProduct(row)'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-eye'>
                           <path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path>
                           <circle cx='12' cy='12' r='3'></circle>
                        </svg>
                     </the-link>
                  </td>
               </template>
            </TheTable>
         </div>
      </div>
   </Layout>

   <view-product-modal v-model:order='order' />
</template>

<script setup lang='ts'>
   import axios from 'axios';
   import { reactive, ref } from 'vue';
   import dateFormat from 'dateformat';
   import fileSaver from 'file-saver';

   import { Components, Models, Utils } from '~/types';
   import { usePagination, useTableCheckbox, useToast } from '~/uses';
   import { useRoute } from '~/utils';

   import Layout from './Layout.vue';
   import TheTable from '~/components/Table/TheTable.vue';
   import TheFilterTableCard from '~/components/Table/TheFilterTableCard.vue';
   import ViewProductModal from '~/pages/Orders/ViewProductModal.vue';

   const props = defineProps<{
      statusHtmlLabel: Components.Table.FilterCard.defineField
      statusLabel: Components.Table.FilterCard.defineField
      services: Components.Table.FilterCard.defineField
      paginationData: Utils.Pagination.Type<Models.Order>
   }>();

   const order = ref<Models.Order | null>(null);
   const columns = reactive<Components.Table.Columns<Models.Order>>([
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
   const filterFields = reactive<Components.Table.FilterCard.Fields>([
      {
         name: 'service_id',
         label: 'Filter by Product',
         placeholder: 'Product',
         options: props.services,
      },
   ]);

   const { search, perPage } = usePagination();
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
      // return Inertia.post(useRoute('product.export', { action: 'view' }), { includes: [id.toString()] }, {
      //    headers: {
      //       'Content-Type': 'application/json',
      //    },
      // });

      order.value = row;
   };
</script>
