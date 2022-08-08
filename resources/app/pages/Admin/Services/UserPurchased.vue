<template>
   <the-head>
      <title>Purchase History of {{ service.name }}</title>
   </the-head>
   <crud-layout :title='`Purchase History of ${service.name}`'>
      <template v-if='!!selected.length' #selected-action>
         <div class='flex items-center'>
            <div class='hidden md:block text-sm italic mr-2 whitespace-nowrap'><span>{{ selected.length }}</span> items
               selected
            </div>
            <v-button variant='danger' size='sm' @click='() => bulkDelete()'>
               Delete
            </v-button>
         </div>
      </template>
      <v-crud-table title='Indicate the number of products purchased from this service by the user.'
                    :columns='columns'
                    :data='paginationData'
                    has-checkbox
                    @selected-rows='onSelectedRows'
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
         <template #row-action='{row}'>
            <td class='table--col'>
               <the-link
                  :href='$route("admin.service.user-purchased.destroy", {service: service.id, user: row.id})'
                  method='delete'
                  as='button'
               >
                  <v-button size='xs' variant='warning' outline only-icon>
                     <template #icon>
                        <font-awesome-icon icon='fa-solid fa-rotate-left' class='w-full h-full' />
                     </template>
                  </v-button>
               </the-link>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>
</template>

<script setup lang='ts'>
   import { usePagination, useTableCheckbox } from '~/uses';
   import { numberFormat, useRoute } from '~/utils';

   import type { Models } from '~/types/Models';
   import type { Utils } from '~/types/Utils';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VUserLabel from '~/components/VUserLabel.vue';
   import VButton from '~/components/VButton.vue';
   import { Inertia } from '@inertiajs/inertia';

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
      {
         path: 'action',
         label: '',
      },
   ]);
   const { selected, onSelected: onSelectedRows } = useTableCheckbox();

   const bulkDelete = () => {
      if (confirm('Warning! You won\'t be able to undo the deletion once you confirm it. Are you certain?') === false) return;

      const url = useRoute('admin.service.user-purchased.bulk-destroy', {
         service: props.service.id,
      });
      const data = { includes: selected };

      return Inertia.post(url, data);
   };
</script>
