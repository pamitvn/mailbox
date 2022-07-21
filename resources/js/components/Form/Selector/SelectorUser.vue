<template>
   <label class='small mb-1' :for='elmId'>{{ label }}</label>
   <the-select
      v-model='selected'
      :filterable='false'
      :options='options'
      :multiple='props.multiple'
      :reduce='reduceOption'
      :disabled='props.disabled'
      placeholder='Select user'
      @search='onSearch'

   >
      <template #search='{attributes, events}'>
         <input
            class='vs__search'
            v-bind='attributes'
            v-on='events'
            :id='elmId'
         />
      </template>
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
      id: string;
      label: string;
      email: string;
   }

   const elmId = `user_selector_${(Math.random() + 1).toString(36).substring(7)}`;

   const props = withDefaults(defineProps<{
      modelValue: string | number | null
      label?: string
      error?: string
      allowChange?: boolean
      multiple?: boolean
      disabled?: boolean
   }>(), {
      allowChange: true,
      multiple: false,
      disabled: false,
   });
   const emit = defineEmits(['update:modelValue']);

   const options = ref([]);
   const selected = ref(null);

   const reduceOption = option => option.id;
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
            id: item.id,
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
      emit('update:modelValue', _.get(item, 'id', val));
   });
</script>

<style>
   .vs__validator-invalid {
      border-color: #e81500;
   }
</style>
