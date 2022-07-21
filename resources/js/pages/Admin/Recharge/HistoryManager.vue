<template>
   <the-head>
      <title>Recharge Histories</title>
   </the-head>
   <Layout>
      <div class='card'>
         <div class='card-body'>
            <TheTable v-model:search='search' v-model:per-page='perPage' :data='paginationData' :columns='columns'>
               <template #row-user='{value}'>
                  <td class='w-auto'>
                     <user-label :data='value' :has-avatar='true' :gravatar='value.email' />
                  </td>
               </template>
               <template #row-action='{row}'>
                  <td>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark'
                               :href='$route("admin.recharge-history.destroy", row.id)'
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

   import Layout from './Layout.vue';
   import TheTable from '~/components/Table/TheTable.vue';
   import UserLabel from '~/components/UserLabel.vue';

   const props = defineProps<{
      paginationData: Utils.Pagination.Type<Models.RechargeHistory>
   }>();

   const { search, perPage } = usePagination();
   const columns = reactive([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'user',
         label: 'User',
      },
      {
         path: 'bank.name',
         label: 'Bank',
      },
      {
         path: 'amount',
         label: 'Amount',
      },
      {
         path: 'before_transaction',
         label: 'Before Transaction',
      },
      {
         path: 'after_transaction',
         label: 'After Transaction',
      },
      {
         path: 'note',
         label: 'Note',
      },
      {
         path: 'action',
         label: '',
      },
   ]);
</script>
