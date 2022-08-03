<template>
   <label class='small mb-1' :for='elmId'>{{ label }}</label>
   <vue-select
      v-model='selected'
      :filterable='false'
      :options='options'
      :reduce='reduceOption'
      :multiple='props.multiple'
      :disabled='props.disabled'
      placeholder='Select user'
      class='rounded-md'
      :class='!!error && "border border-rose-300"'
      @search='onSearch'
   >
      <template #search='{attributes, events}'>
         <input
            class='form-input vs__search !border-0 !shadow-none'
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
         <div class='flex justify-start items-center space-x-2'>
            <vue-gravatar :email='email' default-img='mp' :size='25' :alt='label' />
            <span>{{ label }}</span>
         </div>
      </template>
      <template #selected-option='{label, email}'>
         <div class='selected flex justify-center items-center space-x-2'>
            <vue-gravatar :email='email' default-img='mp' :size='25' :alt='label' />
            <span>{{ label }}</span>
         </div>
      </template>
   </vue-select>
   <div v-if='error' class='text-xs mt-1 !text-rose-500'>
      {{ error }}
   </div>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { ref, watch, watchEffect } from 'vue';

   import { DoAction } from '~/utils';
   import { useToast } from '~/uses';

   import type { Models } from '~/types/Models';

   interface RecordType {
      id: string;
      label: string;
      email: string;
   }

   const elmId = `user_selector_${(Math.random() + 1).toString(36).substring(7)}`;

   const props = withDefaults(defineProps<{
      modelValue: string | number | any[] | null
      options?: object | object[]
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

   const options = ref<object | object[]>(props.options || []);
   const selected = ref<string | number | any[] | null>(props.modelValue || null);

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

         options.value = _.filter(options.value, ite => _.includes(selected.value as any, ite?.id));
         options.value.push(
            ..._.compact(
               _.filter(
                  _.map(action.data ?? [], (item: Models.User) => ({
                        label: item.name ?? item.username ?? 'Unknown Name',
                        email: item.email ?? '',
                        id: item.id,
                     }),
                  ), i => !_.filter(options.value, { id: i.id }).length,
               ),
            ),
         );
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


   watch(() => props.options, (val) => {
      if (!(val && props.options?.length) || options.value === val) return;

      options.value = props.options;
   });

   watch(() => props.modelValue, () => {
      if (!props.allowChange) return;

      selected.value = props.modelValue;
   });

   watch(selected, (val) => {
      const item = val as RecordType | RecordType[];
      const value = _.isArray(item) ? _.compact(item) : _.get(item, 'id', val);

      if (val === props.modelValue) return;

      emit('update:modelValue', value);
   });
</script>

<style>
   .vs__validator-invalid {
      border-color: #e81500;
   }
</style>
