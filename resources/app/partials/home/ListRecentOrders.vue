<template>
   <v-crud-table :data='records'
                 :columns='columns'
                 :has-pagination='false'
                 title='Recent Orders'
   >
      <template #custom-service-header='{label}'>
         <th class='table-col pl-6'>
            <div class='font-semibold w-full text-left'
                 :class='isMobile && "text-center"'
            >
               <span>{{ label }}</span>
            </div>
         </th>
      </template>
      <template #row-service='{value}'>
         <td class='table--col text-left' style='max-width: 20%'>
            <div>
               <div class='flex space-x-2 justify-center align-items-center'>
                  <div class='shrink'>
                     <img v-if='value.feature_image' class='rounded-full'
                          :src='`/storage/${value.feature_image}`'
                          width='32'
                          height='32'
                     >
                  </div>
                  <div class='grow ms-2'>
                     <span class='text-dark'><b>{{ value.name }}</b></span>
                  </div>
               </div>
            </div>
         </td>
      </template>
      <template #row-user.username='{value}'>
         <td class='table--col'>
            <div class='font-medium text-red-500'>
               {{ value }}
            </div>
         </td>
      </template>
      <template #row-quantity='{value}'>
         <td class='table--col'>
            <div class='font-medium text-green-500'>
               {{ value }}
            </div>
         </td>
      </template>
      <template #row-price='{value}'>
         <td class='table--col'>
            <div class='font-medium text-indigo-600'>
               {{ value }}
            </div>
         </td>
      </template>
   </v-crud-table>
</template>

<script setup lang='ts'>
   import axios from 'axios';
   import { onMounted, onUnmounted, ref } from 'vue';
   import { useRoute, numberFormat } from '~/utils';
   import { useMobile, usePagination } from '~/uses';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';

   const records = ref([]);

   const { isMobile } = useMobile();
   const { columns } = usePagination([
      {
         path: 'service',
         label: 'Service',
         showMobile: true,
      },
      {
         path: 'user.username',
         label: 'User',
         showMobile: true,
      },
      {
         path: 'quantity',
         label: 'Quantity',
         showMobile: true,
      },
      {
         path: 'price',
         label: 'Price',
      },
      {
         path: 'created_at',
         label: 'Time',
      },
   ]);

   const fetchRecords = async () => {
      try {
         const endpoint = useRoute('recent-orders');

         const { data } = await axios.get<object[]>(endpoint);

         if (!data || !data.length) throw 'Empty';

         records.value = data;
      } catch (e) {
         console.log('Fetching Recent Orders :', e);
      }
   };

   onMounted(() => {
      fetchRecords();

      const intervalId = setInterval(fetchRecords, 3000);

      onUnmounted(
         () => {
            clearInterval(intervalId);
         },
      );
   });
</script>
