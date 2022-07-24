<template>
   <div class='bg-white shadow-lg rounded-sm border border-slate-200 relative'>
      <header v-if='title' class='px-5 py-4'>
         <h2 class='font-semibold text-slate-800'>{{ title }}</h2>
      </header>
      <div>
         <!-- Table -->
         <div class='overflow-x-auto'>
            <table class='table-auto w-full divide-y divide-slate-200'>
               <!-- Table header -->
               <thead class='text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-slate-200'>
               <tr>
                  <th v-if='hasCheckbox' class='table--col w-px'>
                     <div class='flex items-center'>
                        <label class='inline-flex'>
                           <span class='sr-only'>Select all</span>
                           <input class='form-checkbox' type='checkbox' v-model='selectAll' />
                        </label>
                     </div>
                  </th>
                  <slot v-for='(col, index) in getColumns' :key='index' :name='`custom-${col.path}-header`'
                        v-bind='{col, label: getColLabel(col)}'>
                     <th class='table--col'>
                        <div class='font-semibold'>{{ getColLabel(col) }}</div>
                     </th>
                  </slot>
               </tr>
               </thead>
               <!-- Table body -->
               <tbody class='text-sm divide-y divide-slate-200'>
               <template v-if='getTableData.length'>
                  <slot name='rows' :data='data'>
                     <tr v-for='(row, index) in getTableData' :key='index'>
                        <td v-if='hasCheckbox' class='px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px'>
                           <div class='flex items-center'>
                              <label class='inline-flex'>
                                 <span class='sr-only'>Select</span>
                                 <input
                                    class='form-checkbox'
                                    type='checkbox'
                                    :checked='selected[getCheckboxField(row)]'
                                    @input='(val) => onSelected(row, val)' />
                              </label>
                           </div>
                        </td>
                        <slot v-for='(col, i) in getColumns'
                              :key='i'
                              :name='`row-${col.path}`'
                              :column='col'
                              :row='row'
                              :value='getRowDisplay(row, col)'
                        >
                           <td class='table--col'>
                              <div class='font-medium'>
                                 {{ getRowDisplay(row, col) }}
                              </div>
                           </td>
                        </slot>
                     </tr>
                  </slot>
               </template>
               <template v-else>
                  <tr>
                     <td class='text-center py-2'
                         :colspan='getColumns.length ?? 10'>
                        <span>Empty Table</span>
                     </td>
                  </tr>
               </template>
               </tbody>
            </table>

         </div>
      </div>
   </div>

   <!-- Pagination -->
   <div v-if='hasPagination && (data.prev_cursor || data.next_cursor)' class='mt-8'>
      <div class='flex flex-col sm:flex-row sm:items-center sm:justify-between lg:justify-end'>
         <nav class='mb-4 sm:mb-0 sm:order-1' role='navigation' aria-label='Navigation'>
            <ul class='flex justify-between'>
               <li class='ml-3 first:ml-0'>
                  <the-link
                     :data='{cursor: data.prev_cursor}'
                     :disabled='!data.prev_cursor'
                     replace
                     preserve-state
                  >
                     <v-button type='button' variant='info' outline :disabled='!data.prev_cursor'>
                        &lt;- Previous
                     </v-button>
                  </the-link>
               </li>
               <li class='ml-3 first:ml-0'>
                  <the-link :data='{cursor: data.next_cursor}'
                            :disabled='!data.next_cursor'
                            replace
                            preserve-state>
                     <v-button type='button' variant='info' outline :disabled='!data.next_cursor'>
                        Next -&gt;
                     </v-button>
                  </the-link>
               </li>
            </ul>
         </nav>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, watch, watchEffect } from 'vue';
   import { useMobile } from '~/uses';

   import type Table from '~/types/Components/Table';
   import VButton from '~/components/VButton.vue';
   import { isURL } from '~/utils';
   import qs from 'query-string';
   import { usePage } from '@inertiajs/inertia-vue3';

   const props = withDefaults(defineProps<{
      title?: string

      checkboxByField?: string;

      hasPagination?: boolean;
      hasCheckbox?: boolean;
      hasHideMobile?: boolean;

      columns: Table.Columns;
      data: any;
   }>(), {
      checkboxByField: 'id',
      hasPagination: true,
      hasCheckbox: false,
      hasHideMobile: false,
   });
   const emit = defineEmits(['selectedRows']);

   const { isMobile } = useMobile();

   const selectAll = ref(false);
   const selected = ref({});

   const useUrl = computed(() => usePage().url.value);
   const getColumns = computed(() => {
      const columns = props.columns;

      return props.hasHideMobile && isMobile.value
         ? _.filter(columns, { showMobile: true })
         : columns;
   });
   const getTableData = computed(() => props.hasPagination ? _.get(props.data, 'data', []) : props.data);

   const getColLabel = (col) => _.get(col, 'label');
   const getRowDisplay = (row, col) => {
      const displayCallback = _.get(col, 'display');

      if (displayCallback && _.isFunction(displayCallback)) return displayCallback(row, _.get(col, 'path'), _);

      return _.get(row, col.path);
   };
   const getCheckboxField = (row) => _.get(row, props.checkboxByField, 'id');
   const onSelected = (row, val) => {
      selected.value[getCheckboxField(row)] = _.get(val, 'target.checked', false);
   };


   watch(selectAll, () => {
      _.forEach(getTableData.value, item => {
         selected.value[getCheckboxField(item)] = !!selectAll.value;
      });
   });

   watch(() => getTableData.value, () => {
      selected.value = {};
   });

   watchEffect(() => {
      emit('selectedRows', selected.value);
   });

</script>

<style lang='scss'>
   .table {
      &-- {
         &col {
            @apply px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap text-center;
         }
      }
   }
</style>
