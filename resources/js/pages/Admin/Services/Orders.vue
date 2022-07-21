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
                      :data='paginationData'
                      :columns='columns'
                      :has-checkbox='true'
                      @selected-rows='onSelectedRows'
            >
               <template #header-actions>
                  <button class='btn btn-danger btn-xs' @click='() => bulkDeleter()'>
                     <i class='fa-solid fa-trash-can me-1'></i>
                     Delete (Selected rows)
                  </button>
               </template>
               <template #row-user='{value}'>
                  <td style='width: 34%'>
                     <user-label :data='value'
                                 :gravatar='value?.email'
                                 :has-avatar='true' />
                  </td>
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
   import { reactive } from 'vue';
   import dateFormat from 'dateformat';
   import { Components, Models, Utils } from '~/types';
   import { usePagination, useTableCheckbox } from '~/uses';

   import Layout from './Layout.vue';
   import TheTable from '~/components/Table/TheTable.vue';
   import TheFilterTableCard from '~/components/Table/TheFilterTableCard.vue';
   import UserLabel from '~/components/UserLabel.vue';
   import { useRoute } from '~/utils';
   import { Inertia } from '@inertiajs/inertia';

   const props = defineProps<{
      statusHtmlLabel: Components.Table.FilterCard.defineField
      statusLabel: Components.Table.FilterCard.defineField
      service: Models.Service;
      paginationData: Utils.Pagination.Type<Models.Order>
   }>();

   const columns = reactive<Components.Table.Columns<Models.Order>>([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'user',
         label: 'User',
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
   ]);

   const { search, perPage } = usePagination();
   const { selected, onSelected: onSelectedRows } = useTableCheckbox();

   const bulkDeleter = () => {
      if (confirm('Warning! You won\'t be able to undo the deletion once you confirm it. Are you certain?') === false) return;

      const url = useRoute('admin.service.order.bulk-destroy', {
         service: props.service.id,
      });

      const data = { includes: selected };

      return Inertia.post(url, data);
   };
</script>
