<template>
   <div class='container-xl px-4 mt-5'>
      <div v-if='notification.enable' class='alert alert-primary alert-solid alert-icon' role='alert'>
         <div class='alert-icon-aside'>
            <i class='far fa-flag'></i>
         </div>
         <div class='alert-icon-content'>
            <h6 v-if='notification.title' class='alert-heading'>
               {{ notification.title }}
            </h6>
            <div v-html='notification.content'></div>
         </div>
      </div>

      <div class='card'>
         <div class='card-body'>
            <the-table v-model:search='search'
                       :data='records'
                       :columns='columns'
                       :is-pagination='false'
                       :has-select-per-page='false'
                       :has-hide-mobile='true'
            >
               <template #row-service='{row}'>
                  <td style='width: 30%'>
                     <div class='d-flex g-2 align-items-center'>
                        <div class='flex-shrink-0 avatar avatar-lg'>
                           <img class='avatar-img img-fluid'
                                :src='`/storage/${row.feature_image}`'
                                :alt='row.name'
                                width='100'
                                height='100'
                           >
                        </div>
                        <div class='flex-grow-1 ms-2'>
                           <span class='text-dark'><b>{{ row.name }}</b></span>
                        </div>
                     </div>
                  </td>
               </template>
               <template #row-pop3='{value}'>
                  <td style='width: 3%'>
                     <svg v-if='value' width='25' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                        <!--! Font Awesome Pro 6.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                        <path
                           d='M235.3 331.3C229.1 337.6 218.9 337.6 212.7 331.3L148.7 267.3C142.4 261.1 142.4 250.9 148.7 244.7C154.9 238.4 165.1 238.4 171.3 244.7L224 297.4L340.7 180.7C346.9 174.4 357.1 174.4 363.3 180.7C369.6 186.9 369.6 197.1 363.3 203.3L235.3 331.3zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 32C132.3 32 32 132.3 32 256C32 379.7 132.3 480 256 480C379.7 480 480 379.7 480 256C480 132.3 379.7 32 256 32z' />
                     </svg>
                  </td>
               </template>
               <template #row-imap='{value}'>
                  <td style='width: 3%'>
                     <svg v-if='value' width='25' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'>
                        <!--! Font Awesome Pro 6.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. -->
                        <path
                           d='M235.3 331.3C229.1 337.6 218.9 337.6 212.7 331.3L148.7 267.3C142.4 261.1 142.4 250.9 148.7 244.7C154.9 238.4 165.1 238.4 171.3 244.7L224 297.4L340.7 180.7C346.9 174.4 357.1 174.4 363.3 180.7C369.6 186.9 369.6 197.1 363.3 203.3L235.3 331.3zM512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 32C132.3 32 32 132.3 32 256C32 379.7 132.3 480 256 480C379.7 480 480 379.7 480 256C480 132.3 379.7 32 256 32z' />
                     </svg>
                  </td>
               </template>
               <template #row-lifetime='{value}'>
                  <td style='width: 3%'>
                     {{ value }}
                  </td>
               </template>
               <template #row-price='{value}'>
                  <td style='width: 3%'>
                     {{ value }}
                  </td>
               </template>
               <template #row-in_stock_count='{value}'>
                  <td style='width: 15%'>
                     {{ value }}
                  </td>
               </template>
               <template #row-actions='{row}'>
                  <td style='width: 10%'>
                     <button class='btn btn-sm btn-info' @click='() => onBuy(row)'>
                        Buy
                     </button>
                  </td>
               </template>
            </the-table>
         </div>
      </div>
   </div>

   <buy-product-modal v-model:service='service' />
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, onMounted, onUnmounted, reactive, ref, watchEffect } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';
   import { Components, Models } from '~/types';
   import { useCreateUpdateSocket, usePagination } from '~/uses';

   import TheTable from '~/components/Table/TheTable.vue';
   import BuyProductModal from '~/components/Product/BuyProductModal.vue';
   import { Echo } from '~/utils';

   const props = defineProps<{
      services: Models.Service[]
   }>();

   const notification = computed(() => _.get(usePage().props.value, 'notification', {
      enable: false,
   }));

   const service = ref<Models.Service | null>(null);
   const columns = reactive<Components.Table.Columns<Models.Service>>([
      {
         path: 'service',
         label: 'Service',
         showMobile: true,
      },
      {
         path: 'lifetime',
         label: 'Lifetime',
      },
      {
         path: 'pop3',
         label: 'Pop3',
      },
      {
         path: 'imap',
         label: 'Imap',
      },
      {
         path: 'price',
         label: 'Price',
      },
      {
         path: 'in_stock_count',
         label: 'In Stock',
         showMobile: true,
      },
      {
         path: 'actions',
         label: '',
         showMobile: true,
      },
   ]);

   const { search } = usePagination();
   const { records, setRecords } = useCreateUpdateSocket('service', { update: '.stocks' }, {
      privateChannel: false,
   });

   const onBuy = (row: Models.Service) => {
      service.value = row;
   };

   onMounted(() => {
      const channel = Echo.channel('product');

      const handlingCheckCount = (e) => {
         const cloneRecords: Models.Service[] = _.cloneDeep(records.value);

         _.forEach(_.keys(e), (key) => {
            const value = e[key];
            const index = _.findIndex(cloneRecords, item => !item.is_local && _.get(item, 'extras.parent_count_key') === key);

            if (index === -1) return;

            _.set(cloneRecords, `${index}.in_stock_count`, value);
         });

         setRecords(cloneRecords);
      };

      channel.listen('.count', handlingCheckCount);

      onUnmounted(() => channel.stopListening('.count', handlingCheckCount));
   });

   watchEffect(() => {
      setRecords(_.cloneDeep(props.services));
   });
</script>
