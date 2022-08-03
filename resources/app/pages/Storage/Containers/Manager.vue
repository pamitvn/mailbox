<template>
   <the-head>
      <title>Container of {{ storage?.name }}</title>
   </the-head>
   <crud-layout :title='`Container of ${storage?.name}`'
                :filter-fields='filterFields'
                has-search
                has-more-action
   >
      <template #header-action>
         <container-import-product-modal :storage='storage' />

         <the-link :href='$route("storage.index")'>
            <v-button variant='dark' outline>
               Back to list
            </v-button>
         </the-link>
      </template>

      <template v-if='!!selected.length' #more-action-left>
         <div class='flex items-center'>
            <div class='hidden md:block text-sm italic mr-2 whitespace-nowrap'><span>{{ selected.length }}</span> items
               selected
            </div>
            <v-button variant='danger' size='sm' @click='() => bulkDeleter()'>
               Delete
            </v-button>

            <v-button class='ml-2' variant='warning' size='sm' @click='() => bulkExport()'>
               Export
            </v-button>

            <v-button class='ml-2' variant='warning' size='sm' @click='() => bulkExport(true)'>
               Export And Delete
            </v-button>
         </div>
      </template>

      <v-crud-table
         :columns='columns'
         :data='paginationData'
         has-checkbox
         title='All storages'
         @selected-rows='onSelectedRows'
      >
         <template #row-status='{value}'>
            <td class='table--col'>
               <div v-html='statusHtmlLabel[value]'></div>
            </td>
         </template>
         <template #row-action='{row}'>
            <td class='table--col'>
               <the-link
                  :href='$route("storage.container.destroy", {storage: storage.id, container: row.id})'
                  method='delete'
                  as='button'
                  class='ml-2'
               >
                  <v-button size='xs' variant='danger' outline only-icon>
                     <template #icon>
                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none'
                             stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                             class='w-full h-full'>
                           <polyline points='3 6 5 6 21 6'></polyline>
                           <path
                              d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path>
                           <line x1='10' y1='11' x2='10' y2='17'></line>
                           <line x1='14' y1='11' x2='14' y2='17'></line>
                        </svg>
                     </template>
                  </v-button>
               </the-link>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>
</template>

<script setup lang='ts'>
   import { reactive } from 'vue';
   import { Inertia } from '@inertiajs/inertia';
   import dateFormat from 'dateformat';

   import { usePagination, useTableCheckbox, useToast } from '~/uses';
   import { useRoute } from '~/utils';

   import type { Utils } from '~/types/Utils';
   import type { Models } from '~/types/Models';
   import type { Components } from '~/types/Components';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';
   import ContainerImportProductModal from '~/partials/storages/ContainerImportProductModal.vue';
   import axios from 'axios';
   import fileSaver from 'file-saver';

   const props = defineProps<{
      statusHtmlLabel: Record<string, string>
      statusLabel: Record<string, string>
      storage: Models.Storage
      paginationData: Utils.Pagination.Cursor<Models.Storage>
   }>();

   const filterFields = reactive<Components.Table.FilterCard.Fields>([
      {
         name: 'status',
         label: 'Filter by status',
         placeholder: 'Status',
         options: props.statusLabel,
      },
   ]);

   const { search, perPage, columns } = usePagination([
      {
         path: 'id',
         label: '#',
      },
      {
         path: 'payload',
         label: 'Payload',
         display: (row, path, lodash) => lodash.truncate(lodash.get(row, path, '') as string),
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
   const { selected, onSelected: onSelectedRows } = useTableCheckbox();

   const bulkDeleter = () => {
      if (confirm('Warning! You won\'t be able to undo the deletion once you confirm it. Are you certain?') === false) return;

      const url = useRoute('storages.container.bulk-destroy', {
         storage: props.storage.id,
      });
      const data = { includes: selected };

      return Inertia.post(url, data);
   };

   const bulkExport = async ($delete = false) => {
      const url = useRoute('storages.container.bulk-export', { storage: props.storage.id });
      const data = { includes: selected, delete: $delete };

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

         await fileSaver.saveAs(blob, `[${new Date().toISOString()}] Export ${selected.length} items of container ${props.storage.name} (MailBox).txt`);

         if ($delete) {
            Inertia.reload();
         }
      } catch (e) {
         useToast(`An problem occurred during the download.`, {
            type: 'danger',
         });
      }

   };
</script>
