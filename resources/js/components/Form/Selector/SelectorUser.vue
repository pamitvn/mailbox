<template>
   <the-select
      placeholder='Select user'
      :filterable='false'
      :options='options'
      @search='onSearch'>
   </the-select>
</template>

<script setup lang='ts'>
   import { ref } from 'vue';
   import * as _ from 'lodash';

   const props = defineProps<{
      modelValue: string | number
      label?: string
      error?: string
   }>();
   const emit = defineEmits(['update:modelValue']);

   const options = ref([]);

   const onSearch = function(search: string, loading: (loading: boolean) => void) {
      search = search.trim();

      if (!search) return;

      loading(true);
      fetchData(search, loading);

   };
   const fetchData = _.debounce(async (search: string, loading: (loading: boolean) => void) => {
      console.log(search);

      loading(false);
   }, 500);
</script>
