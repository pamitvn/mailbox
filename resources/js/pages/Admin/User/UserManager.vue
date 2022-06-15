<template>
   <Layout>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.user.create")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-user-plus me-1'>
               <path d='M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2'></path>
               <circle cx='8.5' cy='7' r='4'></circle>
               <line x1='20' y1='8' x2='20' y2='14'></line>
               <line x1='23' y1='11' x2='17' y2='11'></line>
            </svg>
            Add New User
         </the-link>
      </template>
      <div>
         <div class='row'>
            <div class='col-xl-4 col-md-6 mb-4'>
               <!-- Dashboard info widget 2-->
               <div class='card border-start-lg border-start-secondary h-100'>
                  <div class='card-body'>
                     <div class='d-flex align-items-center'>
                        <div class='flex-grow-1'>
                           <div class='small fw-bold text-secondary mb-1'>Total User</div>
                           <div class='h5'>{{ statistics.total }}</div>
                        </div>
                        <div class='ms-2'>
                           <svg class='svg-inline--fa fa-tag fa-2x text-gray-200' aria-hidden='true' focusable='false'
                                data-prefix='fas' data-icon='tag' role='img' xmlns='http://www.w3.org/2000/svg'
                                viewBox='0 0 448 512' data-fa-i2svg=''>
                              <path fill='currentColor'
                                    d='M48 32H197.5C214.5 32 230.7 38.74 242.7 50.75L418.7 226.7C443.7 251.7 443.7 292.3 418.7 317.3L285.3 450.7C260.3 475.7 219.7 475.7 194.7 450.7L18.75 274.7C6.743 262.7 0 246.5 0 229.5V80C0 53.49 21.49 32 48 32L48 32zM112 176C129.7 176 144 161.7 144 144C144 126.3 129.7 112 112 112C94.33 112 80 126.3 80 144C80 161.7 94.33 176 112 176z'></path>
                           </svg><!-- <i class="fas fa-tag fa-2x text-gray-200"></i> Font Awesome fontawesome.com -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class='col-xl-4 col-md-6 mb-4'>
               <!-- Dashboard info widget 1-->
               <div class='card border-start-lg border-start-primary h-100'>
                  <div class='card-body'>
                     <div class='d-flex align-items-center'>
                        <div class='flex-grow-1'>
                           <div class='small fw-bold text-primary mb-1'>Total Balance</div>
                           <div class='h5'>{{ statistics.totalBalance }}</div>
                        </div>
                        <div class='ms-2'>
                           <svg class='svg-inline--fa fa-dollar-sign fa-2x text-gray-200' aria-hidden='true'
                                focusable='false' data-prefix='fas' data-icon='dollar-sign' role='img'
                                xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 512' data-fa-i2svg=''>
                              <path fill='currentColor'
                                    d='M160 0C177.7 0 192 14.33 192 32V67.68C193.6 67.89 195.1 68.12 196.7 68.35C207.3 69.93 238.9 75.02 251.9 78.31C268.1 82.65 279.4 100.1 275 117.2C270.7 134.3 253.3 144.7 236.1 140.4C226.8 137.1 198.5 133.3 187.3 131.7C155.2 126.9 127.7 129.3 108.8 136.5C90.52 143.5 82.93 153.4 80.92 164.5C78.98 175.2 80.45 181.3 82.21 185.1C84.1 189.1 87.79 193.6 95.14 198.5C111.4 209.2 136.2 216.4 168.4 225.1L171.2 225.9C199.6 233.6 234.4 243.1 260.2 260.2C274.3 269.6 287.6 282.3 295.8 299.9C304.1 317.7 305.9 337.7 302.1 358.1C295.1 397 268.1 422.4 236.4 435.6C222.8 441.2 207.8 444.8 192 446.6V480C192 497.7 177.7 512 160 512C142.3 512 128 497.7 128 480V445.1C127.6 445.1 127.1 444.1 126.7 444.9L126.5 444.9C102.2 441.1 62.07 430.6 35 418.6C18.85 411.4 11.58 392.5 18.76 376.3C25.94 360.2 44.85 352.9 60.1 360.1C81.9 369.4 116.3 378.5 136.2 381.6C168.2 386.4 194.5 383.6 212.3 376.4C229.2 369.5 236.9 359.5 239.1 347.5C241 336.8 239.6 330.7 237.8 326.9C235.9 322.9 232.2 318.4 224.9 313.5C208.6 302.8 183.8 295.6 151.6 286.9L148.8 286.1C120.4 278.4 85.58 268.9 59.76 251.8C45.65 242.4 32.43 229.7 24.22 212.1C15.89 194.3 14.08 174.3 17.95 153C25.03 114.1 53.05 89.29 85.96 76.73C98.98 71.76 113.1 68.49 128 66.73V32C128 14.33 142.3 0 160 0V0z'></path>
                           </svg>
                           <!-- <i class="fas fa-dollar-sign fa-2x text-gray-200"></i> Font Awesome fontawesome.com -->
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class='card'>
         <div class='card-body'>
            <TheTable v-model:search='search' v-model:per-page='perPage' :data='paginationData' :columns='columns'>
               <template #row-action='{row}'>
                  <td>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               :href='$route("admin.user.balance", row.id)'
                               as='button'>

                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-dollar-sign'>
                           <line x1='12' y1='1' x2='12' y2='23'></line>
                           <path d='M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'></path>
                        </svg>
                     </the-link>

                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               :href='$route("admin.user.edit", row.id)'
                               as='button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-edit'>
                           <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                           <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                        </svg>
                     </the-link>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark'
                               :href='$route("admin.user.destroy", row.id)'
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
   import TheTable from '~/components/Table/TheTable.vue';
   import Components from '~/types/Components';
   import Table = Components.Table;

   const props = defineProps<{
      paginationData: Utils.Pagination.Type<Models.User>;
      statistics: {
         total: number;
         totalBalance: number
      }
   }>();

   const { search, perPage } = usePagination();
   const columns = reactive<Table.Columns>([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'name',
         label: 'Name',

      },
      {
         path: 'username',
         label: 'Username',

      },
      {
         path: 'email',
         label: 'Email',
      },
      {
         path: 'is_admin',
         label: 'Role',
         display: (row, path, lodash) => lodash.get(row, path, false) ? 'Admin' : 'User',
      },

      {
         path: 'balance',
         label: 'Balance',
         display: (row, path, lodash) => lodash.get(row, path, false),
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
