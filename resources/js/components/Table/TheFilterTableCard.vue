<template>
   <div class='card card-collapsable mb-5'>
      <a class='card-header' :href='`#${id}`' data-bs-toggle='collapse' role='button' aria-expanded='true'
         :aria-controls='id'>
         {{ title }}
         <div class='card-collapsable-arrow'>
            <i class='fas fa-chevron-down'></i>
         </div>
      </a>
      <div class='collapse show' :id='id'>
         <div class='card-body'>
            
         </div>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { onMounted, reactive } from 'vue';
   import { isURL } from '~/utils';
   import FilterCard = Table.FilterCard;
   import Table from '~/types/Components/Table';

   const props = withDefaults(defineProps<{
      id?: string
      title?: string
      fields: FilterCard.Fields
   }>(), {
      id: 'the-filter-table-card',
      title: 'Filter Table',
   });

   const values = reactive({});

   const getCurrentValue = (name) => {
      if (!isURL(location.href)) return null;

      const url = new URL(location.href);
      return url.searchParams.get(name) || null;
   };

   onMounted(() => {
      if (!props.fields?.length) return;

      _.forEach(props.fields, item => {
         values[item.name] = getCurrentValue(item.name);
      });
   });
</script>

