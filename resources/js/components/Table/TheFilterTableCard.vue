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
                  <template v-if='field.is'>
                     <keep-alive>
                        <component :is='field.is' v-bind='field'></component>
                     </keep-alive>
                  </template>
                  <template v-else>
                     <slot :name='`custom-${field.name}`' :field='field'>
                        <label :for='`table-filter-${field.name}-field`' class='small mb-1'>{{ field.label }}</label>
                        <select v-model='values[field.name]'
                                :id='`table-filter-${field.name}-field`'
                                class='form-control'
                        >
                           <option :value='null'>{{ field.placeholder ?? field.label }}</option>
                           <option v-for='(label,index) in field.options' :value='index'>
                              {{ label }}
                           </option>
                        </select>
                     </slot>
                  </template>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { onMounted, ref, watchEffect } from 'vue';
   import { Inertia } from '@inertiajs/inertia';
   import { isURL } from '~/utils';
   import Table from '~/types/Components/Table';

   const props = withDefaults(defineProps<{
      id?: string
      title?: string
      fields: Table.FilterCard.Fields
   }>(), {
      id: 'the-table-filter-card',
      title: 'Table of filters',
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
      const currentURL = window.location.href;
      const url = new URL(currentURL);

      _.forEach(values.value, (item, index) => {
         values.value[index] !== null
            ? url.searchParams.set(String(index), values.value[index])
            : url.searchParams.delete(String(index));
      });

      if (url.toString() === currentURL) return;

      return Inertia.get(url.toString());
   });
</script>

