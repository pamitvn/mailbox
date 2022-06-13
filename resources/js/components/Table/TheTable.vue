<template>
   <div class='dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns'>
      <slot name='table-header' :perPage='perPage' :search='search'>
         <div class='dataTable-top'>
            <div class='dataTable-dropdown'>
               <label>
                  <select v-model='perPage' class='dataTable-selector'>
                     <option value='5'>5</option>
                     <option value='10'>10</option>
                     <option value='15'>15</option>
                     <option value='20'>20</option>
                     <option value='25'>25</option>
                     <option value='50'>50</option>
                     <option value='100'>100</option>
                  </select>
                  Entries per page
               </label>
            </div>
            <div class='dataTable-search'>
               <input v-model='search' class='dataTable-input' placeholder='Search...' type='text'>
            </div>
         </div>
      </slot>
      <div class='dataTable-container'>
         <table class='dataTable-table'>
            <thead>
            <tr>
               <th v-if='hasCheckbox'>
                  <input v-model='selectAll'
                         class='form-check-input'
                         type='checkbox'>
               </th>
               <th v-for='(col, index) in getColumns' :key='index'>
                  <span class='dataTable-sorter'>{{ getColLabel(col) }}</span>
               </th>
            </tr>
            </thead>

            <tbody>
            <template v-if='getTableData.length'>
               <slot name='rows' :data='data'>
                  <tr v-for='(row, index) in getTableData' :key='index'>
                     <td v-if='hasCheckbox'>
                        <slot name='row-checkbox'>
                           <input
                              class='form-check-input'
                              type='checkbox'
                              :checked='selected[getCheckboxField(row)]'
                              @input='(val) => onSelected(row, val)'
                           >
                        </slot>
                     </td>
                     <slot v-for='(col, i) in getColumns'
                           :key='i'
                           :name='`row-${col.path}`'
                           :column='col'
                           :row='row'
                           :value='getRowDisplay(row, col)'
                     >
                        <td>
                           {{ getRowDisplay(row, col) }}
                        </td>
                     </slot>
                  </tr>
               </slot>
            </template>
            <template v-else>
               <tr>
                  <td class='text-center'
                      :colspan='getColumns.length ?? 10'>
                     <span>Empty Table</span>
                  </td>
               </tr>
            </template>
            </tbody>
         </table>
      </div>
      <div class='dataTable-bottom'>
         <div v-if='getTableData.length' class='dataTable-info'>Showing {{ data.from }} to {{ data.to }} of
            {{ data.total }} entries
         </div>
         <nav class='dataTable-pagination'>
            <ul class='dataTable-pagination-list'>
               <template v-for='(item, index) in getLinks' :key='index'>

                  <li v-if='index === 0 && item.url !== null' class='pager'>
                     <Link :href='item.url'>‹</Link>
                  </li>
                  <li v-if='index === (getLinks.length - 1) && item.url !== null' class='pager'>
                     <Link :href='item.url'>›</Link>
                  </li>
                  <li
                     v-if='!(index === 0) && !(index === (getLinks.length - 1))'
                     :class='{active: item.active}'>
                     <Link :href='item.url'>{{ item.label }}</Link>
                  </li>
               </template>

            </ul>
         </nav>
      </div>
   </div>
</template>

<script setup>
   import _ from 'lodash';
   import { computed, ref, watch, watchEffect } from 'vue';
   import { Link, usePage } from '@inertiajs/inertia-vue3';
   import { isURL } from '~/utils';
   import qs from 'querystring';

   const props = defineProps({
      search: {
         type: String,
         required: false,
         default: '',
      },
      page: {
         type: [Number, String],
         required: false,
         default: 1,
      },
      perPage: {
         type: [Number, String],
         required: false,
         default: 10,
      },
      data: {
         type: Object,
         default: {},
      },
      columns: {
         type: Array,
         default: [],
      },
      hasCheckbox: {
         type: Boolean,
         default: false,
      },
      checkboxByField: {
         type: String,
         default: 'id',
      },
   });
   const emit = defineEmits(['update:search', 'update:perPage', 'update:page', 'selectedRows']);

   const search = ref(props.search);
   const perPage = ref(props.perPage);
   const selectAll = ref(false);
   const selected = ref({});

   const useUrl = computed(() => usePage().url.value);
   const getColumns = computed(() => props.columns);
   const getTableData = computed(() => _.get(props.data, 'data', []));
   const getLinks = computed(() => {
      const results = _.get(props.data, 'links', []);
      const currentQuery = qs.parse(_.get(_.split(useUrl.value, '?'), '1'));

      return _.map(results, item => {
         if (!item.url || !isURL(item.url)) return item;

         const targetUrl = new URL(item.url);
         const page = targetUrl.searchParams.get('page') ?? '1';

         _.forEach(currentQuery, (value, key) => {
            targetUrl.searchParams.set(key, value);
         });

         targetUrl.searchParams.set('page', page);
         targetUrl.searchParams.sort();

         item.url = targetUrl.toString();

         return item;
      });
   });
   const hasCheckbox = computed(() => props.hasCheckbox);

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
   const loadCurrentPage = () => {
      const currentQuery = qs.parse(_.get(_.split(useUrl.value, '?'), '1'));
      let page = 1;

      if (!page || page === 1) return;

      page = currentQuery.page;

      emit('update:page', page);
   };

   watch(search, () => {
      emit('update:search', search.value);
   });

   watch(perPage, () => {
      emit('update:perPage', perPage.value);
   });

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

   watchEffect(() => loadCurrentPage());
</script>
