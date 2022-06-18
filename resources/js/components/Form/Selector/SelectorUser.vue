<template>
   <the-select
      v-model='selected'
      placeholder='Select user'
      :filterable='false'
      :options='options'
      @search='onSearch'
   >
      <!--      <template #search='{attributes, events}'>-->
      <!--         <input-->
      <!--            class='vs__search'-->
      <!--            :required='!selected'-->
      <!--            v-bind='attributes'-->
      <!--            v-on='events'-->
      <!--         />-->
      <!--      </template>-->
      <template #no-options='{ search, searching }'>
         <template v-if='searching && search.length >= 2'>
            No results found for <em>{{ search }}</em
         >.
         </template>
         <em v-else style='opacity: 0.5'>To find a user, type at least two characters into the search box.</em>
      </template>
      <template #option='{label, email}'>
         <div class='d-center'>
            <vue-gravatar :email='email' default-img='mp' :size='25' :alt='label' />
            {{ label }}
         </div>
      </template>
      <template #selected-option='{label, email}'>
         <div class='selected d-center'>
            <vue-gravatar :email='email' default-img='mp' :size='25' :alt='label' />
            {{ label }}
         </div>
      </template>
   </the-select>
   <div v-if='error' class='text-red text-sm'>
      {{ error }}
   </div>
</template>

<script setup lang='ts'>
   import * as _ from 'lodash';
   import { ref, watch } from 'vue';

   import { Models } from '~/types';
   import { DoAction } from '~/utils';
   import { useToast } from '~/uses';

   interface RecordType {
      label: string;
      value: string;
      email: string;
   }

   const props = withDefaults(defineProps<{
      modelValue: string | number | null
      label?: string
      error?: string
      allowChange?: boolean
   }>(), {
      allowChange: true,
   });
   const emit = defineEmits(['update:modelValue']);

   const options = ref([]);
   const selected = ref(null);

   const onSearch = function(search: string, loading: (loading: boolean) => void) {
      search = search.trim();

      if (!search || search.length < 2) {
         loading(false);
         return;
      }

      loading(true);
      fetchData(search, loading);

   };
   const fetchData = _.debounce(async (search: string, loading: (loading: boolean) => void) => {
      try {
         const action = await DoAction<Models.User[]>('selector', {
            params: {
               type: 'user',
               search,
            },
         });

         if (!action.success) {
            throw action.message;
         }

         options.value = _.map(action.data ?? [], (item: Models.User) => ({
            label: item.name ?? item.username ?? 'Unknown Name',
            email: item.email ?? '',
            value: item.id,
         }));
      } catch (e) {
         const message = _.isString(e) ? e : e.message;

         if (message) {
            useToast(message, {
               type: 'danger',
            });
         }
      } finally {
         loading(false);
      }
   }, 1000);

   watch(() => props.modelValue, () => {
      if (!props.allowChange) return;

      selected.value = props.modelValue;
   });
   watch(selected, (val) => {
      const item = val as RecordType;
      emit('update:modelValue', _.get(item, 'value', null));
   });
</script>

<style>
   .vs__validator-invalid {
      border-color: #e81500;
   }
</style>
