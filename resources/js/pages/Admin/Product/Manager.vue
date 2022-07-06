<template>
   <the-head>
      <title>Manager Product of {{ service.name }}</title>
   </the-head>
   <Layout :service='service'>
      <template #header-link>
         <import-modal :service='service' />
      </template>
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
                  <button class='btn btn-danger btn-xs' @click='() => bulkDeleter()'>
                     <i class='fa-solid fa-trash-can me-1'></i>
                     Delete (Selected rows)
                  </button>
               </template>
               <template #row-status='{value}'>
                  <td v-html='statusHtmlLabel[value]'></td>
               </template>

               <template #row-action='{row}'>
                  <td>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark'
                               :href='$route("admin.service.product.destroy", row.id)'
                               method='delete'
                               as='button'
                     >
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-trash-2'>
                           <polyline points='3 6 5 6 21 6'></polyline>
                           <path
                              d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                           <line x1='10' y1='11' x2='10' y2='17'></line>
                           <line x1='14' y1='11' x2='14' y2='17'></line>
                        </svg>
                     </the-link>
                  </td>
               </template>
            </TheTable>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { reactive, watch } from 'vue';
   import { Inertia } from '@inertiajs/inertia';
   import dateFormat from 'dateformat';
   import { Components, Models, Utils } from '~/types';
   import { useRoute } from '~/utils';
   import { usePagination, usePaginationCUSocket, useTableCheckbox } from '~/uses';

   import Layout from './Layout.vue';
   import TheTable from '~/components/Table/TheTable.vue';
   import ImportModal from '~/pages/Admin/Product/ImportModal.vue';
   import TheFilterTableCard from '~/components/Table/TheFilterTableCard.vue';

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

   const columns = reactive<Components.Table.Columns<Models.Product>>([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'mail',
         label: 'Mail',
      },
      {
         path: 'password',
         label: 'Password',
      },
      {
         path: 'recovery_mail',
         label: 'Recovery Mail',
      },
      {
         path: 'status',
         label: 'Status',
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
         name: 'status',
         label: 'Filter by status',
         placeholder: 'Status',
         options: props.statusLabel,
      },
   ]);

   const { search, perPage } = usePagination();
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
