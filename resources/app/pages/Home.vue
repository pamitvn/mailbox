<template>
   <the-head>
      <title>Homepage</title>
   </the-head>
   <crud-layout title='Stock now' :has-per-page='false' has-search>
      <v-crud-table :data='records'
                    :columns='columns'
                    :has-pagination='false'
                    has-hide-mobile
      >
         <template #custom-service-header='{label}'>
            <th class='table-col pl-6'>
               <div class='font-semibold w-full inline-flex justify-start items-center space-x-2'>
                  <span>
                     <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' class='w-5 h-5'><g fill='none'
                                                                                                    class='nc-icon-wrapper'><path
                        d='M20 8h-3V6c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v10h20V10c0-1.1-.9-2-2-2zM9 6h6v2H9V6zm11 12H4v-3h2v1h2v-1h8v1h2v-1h2v3zm-2-5v-1h-2v1H8v-1H6v1H4v-3h16v3h-2z'
                        fill='currentColor'></path></g></svg>
                  </span>
                  <span>{{ label }}</span>
               </div>
            </th>
         </template>
         <!--         <template #custom-lifetime-header='{label}'>-->
         <!--            <th class='table-col pl-6'>-->
         <!--               <div class='font-semibold w-full inline-flex justify-center items-center space-x-2'>-->
         <!--                  <span>-->
         <!--                     <svg xmlns='http://www.w3.org/2000/svg'-->
         <!--                          viewBox='0 0 24 24'-->
         <!--                          class='w-4 h-4 fill-current shrink-0'>-->
         <!--                        <g stroke-linecap='square' stroke-miterlimit='10'-->
         <!--                           fill='none' stroke='currentColor' stroke-linejoin='miter'-->
         <!--                           class='nc-icon-wrapper'>-->
         <!--                           <line x1='1' y1='9' x2='23' y2='9'></line>-->
         <!--                           <rect x='1' y='3' width='22' height='20' rx='2'></rect>-->
         <!--                           <line x1='6' y1='1' x2='5.999' y2='5'></line>-->
         <!--                           <line x1='18' y1='1' x2='17.999' y2='5'></line>-->
         <!--                        </g>-->
         <!--                     </svg>-->
         <!--                  </span>-->
         <!--                  <span>{{ label }}</span>-->
         <!--               </div>-->
         <!--            </th>-->
         <!--         </template>-->
         <!--         <template #custom-pop3-header='{label}'>-->
         <!--            <th class='table-col pl-6'>-->
         <!--               <div class='font-semibold w-full inline-flex justify-center items-center space-x-2'>-->
         <!--                  <span>-->
         <!--                     <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48' class='w-4 h-4 fill-current shrink-0'>-->
         <!--                        <g fill='currentColor' class='nc-icon-wrapper'>-->
         <!--                           <path d='M11.631,36.841,1,26l3-3,8,5L35,4l3,2L14.65,36.653A2,2,0,0,1,11.631,36.841Z'-->
         <!--                                 data-color='color-2'></path>-->
         <!--                           <path d='M46,13H41a1,1,0,0,1,0-2h5a1,1,0,0,1,0,2Z'></path>-->
         <!--                           <path d='M46,22H34a1,1,0,0,1,0-2H46a1,1,0,0,1,0,2Z'></path>-->
         <!--                           <path d='M46,31H27a1,1,0,0,1,0-2H46a1,1,0,0,1,0,2Z'></path>-->
         <!--                           <path d='M46,40H20a1,1,0,0,1,0-2H46a1,1,0,0,1,0,2Z'></path>-->
         <!--                        </g>-->
         <!--                     </svg>-->
         <!--                  </span>-->
         <!--                  <span>{{ label }}</span>-->
         <!--               </div>-->
         <!--            </th>-->
         <!--         </template>-->
         <!--         <template #custom-imap-header='{label}'>-->
         <!--            <th class='table-col pl-6'>-->
         <!--               <div class='font-semibold w-full inline-flex justify-center items-center space-x-2'>-->
         <!--                  <span>-->
         <!--                     <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48' class='w-4 h-4 fill-current shrink-0'>-->
         <!--                        <g fill='currentColor' class='nc-icon-wrapper'>-->
         <!--                           <path d='M11.631,36.841,1,26l3-3,8,5L35,4l3,2L14.65,36.653A2,2,0,0,1,11.631,36.841Z'-->
         <!--                                 data-color='color-2'></path>-->
         <!--                           <path d='M46,13H41a1,1,0,0,1,0-2h5a1,1,0,0,1,0,2Z'></path>-->
         <!--                           <path d='M46,22H34a1,1,0,0,1,0-2H46a1,1,0,0,1,0,2Z'></path>-->
         <!--                           <path d='M46,31H27a1,1,0,0,1,0-2H46a1,1,0,0,1,0,2Z'></path>-->
         <!--                           <path d='M46,40H20a1,1,0,0,1,0-2H46a1,1,0,0,1,0,2Z'></path>-->
         <!--                        </g>-->
         <!--                     </svg>-->
         <!--                  </span>-->
         <!--                  <span>{{ label }}</span>-->
         <!--               </div>-->
         <!--            </th>-->
         <!--         </template>-->
         <template #row-service='{row}'>
            <td class='table--col text-left' style='min-width: 20%'>
               <div class='flex space-x-2 justify-center align-items-center'>
                  <div class='shrink'>
                     <img class='rounded-full'
                          :src='`/storage/${row.feature_image}`'
                          :alt='row.name'
                          width='32'
                          height='32'
                     >
                  </div>
                  <div class='grow ms-2'>
                     <span class='text-dark'><b>{{ row.name }}</b></span>
                  </div>
               </div>
            </td>
         </template>
         <template #row-pop3='{value}'>
            <td class='table--col'>
               <div class='font-medium'>
                  <span v-if='value'>
                     <font-awesome-icon icon='fa-regular fa-circle-check' />
                  </span>
                  <span v-else>
                     <font-awesome-icon icon='fa-regular fa-circle-xmark' />
                  </span>
               </div>
            </td>
         </template>
         <template #row-imap='{value}'>
            <td class='table--col'>
               <div class='font-medium'>
                  <span v-if='value'>
                     <font-awesome-icon icon='fa-regular fa-circle-check' />
                  </span>
                  <span v-else>
                     <font-awesome-icon icon='fa-regular fa-circle-xmark' />
                  </span>
               </div>
            </td>
         </template>
         <template #row-price='{value}'>
            <td class='table--col'>
               <div class='font-medium text-emerald-500'>
                  {{ numberFormat(value) }}
               </div>
            </td>
         </template>
         <template #row-in_stock_count='{value}'>
            <td class='table--col'>
               <div class='font-medium text-indigo-600'>
                  {{ numberFormat(value) }}
               </div>
            </td>
         </template>
         <template #row-actions='{row}'>
            <td class='table--col' style='width: 10%'>
               <v-button variant='danger'
                         size='xs'
                         icon outline just-mobile-icon
                         @click='() => onClickBuy(row)'>
                  <template #icon>
                     <font-awesome-icon class='w-full h-full' icon='fa-solid fa-cart-shopping' />
                  </template>
                  Buy
               </v-button>
            </td>
         </template>
      </v-crud-table>
   </crud-layout>
   <buy-product-modal v-model:service='service' />
   <system-notification />
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { onMounted, onUnmounted, ref, watchEffect } from 'vue';

   import { useCreateUpdateSocket, useModal, usePagination } from '~/uses';
   import { Echo, numberFormat } from '~/utils';

   import type { Models } from '~/types/Models';

   import CrudLayout from '~/layouts/CrudLayout.vue';
   import SystemNotification from '~/partials/SystemNotification.vue';
   import BuyProductModal from '~/partials/home/BuyProductModal.vue';
   import VCrudTable from '~/components/CRUD/VCrudTable.vue';
   import VButton from '~/components/VButton.vue';

   const props = defineProps<{
      services: Models.Service[]
   }>();

   const service = ref<Models.Service | null>(null);

   const { columns } = usePagination<Models.Service>([
      {
         path: 'service',
         label: 'Our Product',
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
   const { records, setRecords } = useCreateUpdateSocket('service', { update: '.stocks' }, {
      privateChannel: false,
   });

   const onClickBuy = (item: Models.Service) => {
      service.value = item;
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
