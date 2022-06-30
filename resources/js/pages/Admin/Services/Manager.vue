<template>
   <the-head>
      <title>Service Managers</title>
   </the-head>
   <Layout>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.service.create")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-plus'>
               <line x1='12' y1='5' x2='12' y2='19'></line>
               <line x1='5' y1='12' x2='19' y2='12'></line>
            </svg>
            Add New
         </the-link>
      </template>
      <div class='card'>
         <div class='card-body'>
            <TheTable v-model:search='search' v-model:per-page='perPage' :data='paginationData' :columns='columns'>
               <template #row-feature_image='{row,value}'>
                  <td class='w-25'>
                     <img v-if='value' class='ratio ratio-16x9' :src='`/storage/${value}`' :alt='row.name'>
                  </td>
               </template>
               <template #row-recovery_mail='{value}'>
                  <td style='width: 3%'>
                     <svg v-if='value' width='25' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                        <!--! Font Awesome Pro 6.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                        <path
                           d='M235.3 331.3C229.1 337.6 218.9 337.6 212.7 331.3L148.7 267.3C142.4 261.1 142.4 250.9 148.7 244.7C154.9 238.4 165.1 238.4 171.3 244.7L224 297.4L340.7 180.7C346.9 174.4 357.1 174.4 363.3 180.7C369.6 186.9 369.6 197.1 363.3 203.3L235.3 331.3zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 32C132.3 32 32 132.3 32 256C32 379.7 132.3 480 256 480C379.7 480 480 379.7 480 256C480 132.3 379.7 32 256 32z' />
                     </svg>
                  </td>
               </template>
               <template #row-visible='{value}'>
                  <td style='width: 3%'>
                     <svg v-if='value' width='25' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                        <!--! Font Awesome Pro 6.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                        <path
                           d='M235.3 331.3C229.1 337.6 218.9 337.6 212.7 331.3L148.7 267.3C142.4 261.1 142.4 250.9 148.7 244.7C154.9 238.4 165.1 238.4 171.3 244.7L224 297.4L340.7 180.7C346.9 174.4 357.1 174.4 363.3 180.7C369.6 186.9 369.6 197.1 363.3 203.3L235.3 331.3zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 32C132.3 32 32 132.3 32 256C32 379.7 132.3 480 256 480C379.7 480 480 379.7 480 256C480 132.3 379.7 32 256 32z' />
                     </svg>
                  </td>
               </template>

               <template #row-action='{row}'>
                  <td>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               :href='$route("admin.service.product.index", {service: row.id})'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-database'>
                           <ellipse cx='12' cy='5' rx='9' ry='3'></ellipse>
                           <path d='M21 12c0 1.66-4 3-9 3s-9-1.34-9-3'></path>
                           <path d='M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5'></path>
                        </svg>
                     </the-link>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               :href='$route("admin.service.show", row.id)'
                               as='button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-eye'>
                           <path d='M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z'></path>
                           <circle cx='12' cy='12' r='3'></circle>
                        </svg>
                     </the-link>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark me-2'
                               :href='$route("admin.service.edit", row.id)'
                               as='button'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='feather feather-edit'>
                           <path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path>
                           <path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path>
                        </svg>
                     </the-link>
                     <the-link class='btn btn-datatable btn-icon btn-transparent-dark'
                               :href='$route("admin.service.destroy", row.id)'
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
   import { Models, Utils, Components } from '~/types';
   import { usePagination } from '~/uses';

   import Layout from './Layout';
   import TheTable from '~/components/Table/TheTable';
   import dateFormat from 'dateformat';

   const props = defineProps<{
      paginationData: Utils.Pagination.Type<Models.Service>
   }>();

   const { search, perPage } = usePagination();
   const columns = reactive<Components.Table.Columns<Models.Service>>([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'name',
         label: 'Name',
      },
      {
         path: 'feature_image',
         label: 'Feature Image',
      },
      {
         path: 'recovery_mail',
         label: 'Recovery Mail',
      },
      {
         path: 'visible',
         label: 'Visible',
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
</script>
