<template>
   <the-head>
      <title>Purchase History of {{ service.name }}</title>
   </the-head>
   <crud-layout :title='`Purchase History of ${service.name}`'>
      <v-crud-table title='Indicate the number of products purchased from this service by the user.'
                    :columns='columns'
                    :data='paginationData'
      >
         <template #custom-user-header='{label}'>
            <th class='table--col' style='width: 50%'>
               <div class='font-semibold inline-flex'>
                  {{ label }}
               </div>
            </th>
         </template>
         <template #row-user='{row}'>
            <td class='table--col flex justify-center items-start'>
               <div>
                  <v-user-label :data='row' :gravatar='row.email' has-avatar />
               </div>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>
</template>

<script setup lang='ts'>
   import { usePagination } from '~/uses';
   import { numberFormat } from '~/utils';

   import type { Models } from '~/types/Models';
   import type { Utils } from '~/types/Utils';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VUserLabel from '~/components/VUserLabel.vue';

   const props = defineProps<{
      service: Models.Service
      paginationData: Utils.Pagination.Type<Models.Product>
   }>();

   const { search, perPage, columns } = usePagination([
      {
         path: 'user',
         label: 'User',
      },
      {
         path: 'count',
         label: 'Count',
         display: (row, value, lodash) => numberFormat(lodash.get(row, value, 0)),
      },
   ]);
</script>
