<template>
   <the-head>
      <title>Bank Managers</title>
   </the-head>
   <Layout>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.bank.create")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-dollar-sign'>
               <line x1='12' y1='1' x2='12' y2='23'></line>
               <path d='M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'></path>
            </svg>
            Add New Bank
         </the-link>
      </template>
      <div class='card'>
         <div class='card-body'>
            <TheTable v-model:search='search' v-model:per-page='perPage' :data='paginationData' :columns='columns'>
               <template #row-image='{value,row}'>
                  <td>
                     <img :src='`/storage/${value}`' :alt='row.name' width='25' height='25'>
                  </td>
               </template>
               <template #row-action='{row}'>
                  <td>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               :href='$route("admin.bank.edit", row.id)'
                               as='button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-edit'>
                           <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                           <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                        </svg>
                     </the-link>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark'
                               :href='$route("admin.bank.destroy", row.id)'
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
   import { reactive } from 'vue';
   import { Models, Utils } from '~/types';
   import { usePagination } from '~/uses';

   import Layout from './Layout';
   import TheTable from '~/components/Table/TheTable';

   const props = defineProps<{
      paginationData: Utils.Pagination.Type<Models.Bank>
   }>();

   const { search, perPage } = usePagination();
   const columns = reactive([
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
         display: (row, path, lodash) => lodash.get(row, path, false),
      },
      {
         path: 'action',
         label: '',
      },
   ]);


</script>
