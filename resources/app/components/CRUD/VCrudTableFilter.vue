<template>
   <v-dropdown align='right'>
      <template #dropdown='{close}'>
         <div class='text-xs font-semibold text-slate-400 uppercase pt-1.5 pb-2 px-4'>Filters</div>
         <ul class='mb-4'>
            <li v-for='(field, index) in fields' :key='index' class='py-1 px-3'>
               <keep-alive>
                  <component :is='getComponent(field)'
                             v-model='values[field.name]'
                             v-bind='field'
                  ></component>
               </keep-alive>
            </li>
         </ul>
         <div class='py-2 px-3 border-t border-slate-200 bg-slate-50'>
            <ul class='flex items-center justify-between'>
               <li>
                  <button
                     class='btn-xs bg-white border-slate-200 hover:border-slate-300 text-slate-500 hover:text-slate-600'
                     @click='() => resetField()'>
                     Clear
                  </button>
               </li>
            </ul>
         </div>
      </template>

      <span class='sr-only'>Filter</span>
      <wbr />
      <svg class='w-4 h-4 fill-current' viewBox='0 0 16 16'>
         <path
            d='M9 15H7a1 1 0 010-2h2a1 1 0 010 2zM11 11H5a1 1 0 010-2h6a1 1 0 010 2zM13 7H3a1 1 0 010-2h10a1 1 0 010 2zM15 3H1a1 1 0 010-2h14a1 1 0 010 2z' />
      </svg>
   </v-dropdown>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { onMounted, ref, watchEffect } from 'vue';
   import { Inertia } from '@inertiajs/inertia';

   import { isURL } from '~/utils';
   import type Table from '~/types/Components/Table';

   import VDropdown from '~/components/VDropdown.vue';
   import VSelect from '~/components/Form/VSelect.vue';

   const props = defineProps<{
      fields: Table.FilterCard.Fields
   }>();

   const values = ref({});

   const getComponent = (field: Table.FilterCard.Field) => {
      return _.get(field, 'is', VSelect);
   };
   const getCurrentValue = (name) => {
      if (!isURL(location.href)) return null;

      const url = new URL(location.href);
      return url.searchParams.get(name) || null;
   };
   const resetField = () => {
      _.forEach(_.keys(values.value), key => {
         values.value[key] = null;
      });
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

      return Inertia.replace(url.toString(), {
         preserveState: true,
      });
   });

</script>
