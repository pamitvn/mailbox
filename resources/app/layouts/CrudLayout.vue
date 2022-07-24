<template>
   <default-layout :with-transition='false'>
      <!-- Page header -->
      <div class='sm:flex sm:justify-between sm:items-center mb-4'>

         <!-- Left: Title -->
         <div class='mb-4 sm:mb-0'>
            <slot name='title' v-bind='{title}'>
               <h1 class='text-2xl md:text-3xl text-slate-800 font-bold'>{{ title }} ✨</h1>
            </slot>
         </div>

         <!-- Right: Actions  -->
         <div class='grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2'>
            <slot v-if='!hasMoreAction && (hasPerPage && !hasSearch)' name='selected-action'></slot>
            <form v-if='hasSearch' class='relative' @submit.prevent>
               <label for='action-search' class='sr-only'>Search</label>
               <input v-model='search'
                      id='action-search' class='form-input pl-9 focus:border-slate-300' type='search'
                      placeholder='Search…' />
               <button class='absolute inset-0 right-auto group' type='submit' aria-label='Search'>
                  <svg class='w-4 h-4 shrink-0 fill-current text-slate-400 group-hover:text-slate-500 ml-3 mr-2'
                       viewBox='0 0 16 16' xmlns='http://www.w3.org/2000/svg'>
                     <path
                        d='M7 14c-3.86 0-7-3.14-7-7s3.14-7 7-7 7 3.14 7 7-3.14 7-7 7zM7 2C4.243 2 2 4.243 2 7s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z' />
                     <path
                        d='M15.707 14.293L13.314 11.9a8.019 8.019 0 01-1.414 1.414l2.393 2.393a.997.997 0 001.414 0 .999.999 0 000-1.414z' />
                  </svg>
               </button>
            </form>
            <template v-if='!hasMoreAction && (hasPerPage && !hasSearch)'>
               <v-select-dropdown v-model='perPage'
                                  :options='perPageSelectOptions'
                                  label='Entries per page'
               >
                  <template #icon>
               <span class='w-4 h-4 fill-current text-slate-500 shrink-0 mr-2'>
                  <font-awesome-icon icon='fa-solid fa-list-ol' class='w-full h-full' />
               </span>
                  </template>
               </v-select-dropdown>
               <v-crud-table-filter :fields='filterFields' />
            </template>
            <!-- Add order button -->
            <slot name='header-action'></slot>
         </div>
      </div>

      <!-- More actions -->
      <div class='sm:flex sm:justify-between sm:items-center mb-5'>

         <!-- Left side -->
         <div class='mb-4 sm:mb-0'>
            <slot name='more-action-left'></slot>
         </div>

         <!-- Right side -->
         <div class='grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2'>
            <template v-if='hasMoreAction && hasPerPage || hasSearch'>
               <slot name='selected-action'></slot>
               <v-select-dropdown v-model='perPage'
                                  :options='perPageSelectOptions'
                                  label='Entries per page'
               >
                  <template #icon>
               <span class='w-4 h-4 fill-current text-slate-500 shrink-0 mr-2'>
                  <font-awesome-icon icon='fa-solid fa-list-ol' class='w-full h-full' />
               </span>
                  </template>
               </v-select-dropdown>
               <v-crud-table-filter :fields='filterFields' />
            </template>
            <slot name='more-action-right'></slot>
         </div>
      </div>

      <transition name='fade' mode='out-in' appear>
         <div :key='$page.url'>
            <slot />
         </div>
      </transition>
   </default-layout>
</template>

<script setup lang='ts'>
   import { usePagination } from '~/uses';

   import type { Table } from '~/types/Components/Table';

   import DefaultLayout from './DefaultLayout.vue';
   import VSelectDropdown from '~/components/Form/VSelectDropdown.vue';
   import VCrudTableFilter from '~/components/CRUD/VCrudTableFilter.vue';

   const props = withDefaults(defineProps<{
      title?: string
      hasSearch?: boolean
      hasPerPage?: boolean
      hasMoreAction?: boolean

      filterFields?: Table.FilterCard.Fields
   }>(), {
      hasPerPage: true,
   });

   const { search, perPage, perPageSelectOptions } = usePagination();
</script>
