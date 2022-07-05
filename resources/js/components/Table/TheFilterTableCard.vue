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
            <div class='row'>
               <div v-for='(field, index) in fields' :key='index' class='col-4'>
                  <slot :name='`custom-${field.name}`' :field='field'>
                     <select v-model='values[field.name]' class='form-control'>
                        <option :value='null'>{{ field.label }}</option>
                        <option v-for='(st,index) in field.options' :value='index'>
                           {{ st }}
                        </option>
                     </select>
                  </slot>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { onMounted, ref, watchEffect } from 'vue';
   import { isURL } from '~/utils';
   import Table from '~/types/Components/Table';
   import FilterCard = Table.FilterCard;

   const props = withDefaults(defineProps<{
      id?: string
      title?: string
      fields: FilterCard.Fields
   }>(), {
      id: 'the-filter-table-card',
      title: 'Filter Table',
   });

   const values = ref({});

   const getCurrentValue = (name) => {
      if (!isURL(location.href)) return null;

      const url = new URL(location.href);
      return url.searchParams.get(name) || null;
   };

   onMounted(() => {
      if (!props.fields?.length) return;

      _.forEach(props.fields, item => {
         values.value[item.name] = getCurrentValue(item.name);
      });
   });


   watchEffect(() => {
      console.log(_.cloneDeep(values.value));
   });
</script>

