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
               <template #row-product.status='{value}'>
                  <td v-html='statusHtmlLabel[value]'></td>
               </template>
            </TheTable>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import axios from 'axios';
   import { reactive } from 'vue';
   import dateFormat from 'dateformat';
   import fileSaver from 'file-saver';

   import { Components, Models, Utils } from '~/types';
   import { usePagination, useTableCheckbox, useToast } from '~/uses';
   import { useRoute } from '~/utils';

   import Layout from './Layout.vue';
   import TheTable from '~/components/Table/TheTable.vue';
   import TheFilterTableCard from '~/components/Table/TheFilterTableCard.vue';

   const props = defineProps<{
      statusHtmlLabel: Components.Table.FilterCard.defineField
      statusLabel: Components.Table.FilterCard.defineField
      services: Components.Table.FilterCard.defineField
      paginationData: Utils.Pagination.Type<Models.Order>
   }>();

   const columns = reactive<Components.Table.Columns<Models.Order>>([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'product',
         label: 'Product',
         display: (row, value, lodash) => lodash.get(row, `${value}.service.name`),
      },
      {
         path: 'product.mail',
         label: 'Mail',
      },
      {
         path: 'product.password',
         label: 'Password',
      },
      {
         path: 'product.recovery_mail',
         label: 'Recovery Mail',
      },
      {
         path: 'product.status',
         label: 'Status',
      },
      {
         path: 'product.created_at',
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
         name: 'status',
         label: 'Filter by status',
         placeholder: 'Status',
         options: props.statusLabel,
      },
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
</script>
