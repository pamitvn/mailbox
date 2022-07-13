<template>
   <Head>
      <title>Recharge</title>
   </Head>
   <Layout>
      <div class='row row-cols-md-2 row-cols-xl-3 mb-4'>
         <div v-for='(bank, key) in banks' :key='key' class='col'>
            <div class='card'>
               <div class='card-header text-center'>
                  {{ bank.name }}
               </div>
               <div class='card-body text-center'>
                  <p>Tên tài khoản: <span class='text-red'>{{ bank.accountName }}</span></p>
                  <p>STK: <span class='text-red'>{{ bank.accountId }}</span></p>
                  <p>Nội dung: <span class='text-red'>{{ rechargeCode }}</span></p>
               </div>
            </div>
         </div>
      </div>
      <div class='card'>
         <div class='card-body'>
            <TheTable v-model:search='search' v-model:per-page='perPage' :data='paginationData'
                      :columns='columns'>

            </TheTable>
         </div>
      </div>
   </Layout>
</template>

<script>
   import { defineComponent, reactive } from 'vue';
   import { Head } from '@inertiajs/inertia-vue3';

   import Layout from './Layout';
   import TheTable from '~/components/Table/TheTable';
   import { usePagination } from '~/uses';

   export default defineComponent({
      components: { TheTable, Layout, Head },
      props: {
         rechargeCode: {
            type: String,
            default: '',
         },
         banks: {
            type: Array,
            default: [],
         },
         paginationData: {
            type: Object,
            default: [],
         },
      },
      setup(props) {
         const { search, perPage } = usePagination();
         const columns = reactive([
            {
               path: 'id',
               label: '#',
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

         return { search, perPage, columns };
      },
   });
</script>
