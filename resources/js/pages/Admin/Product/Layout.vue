<template>
   <main>
      <header class='page-header page-header-compact page-header-light border-bottom bg-white mb-4'>
         <div class='container-fluid px-4'>
            <div class='page-header-content'>
               <div class='row align-items-center justify-content-between pt-3'>
                  <div class='col-auto mb-3'>
                     <h1 class='page-header-title'>
                        <div class='page-header-icon'>
                           <slot name='header-title-icon'>

                              <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                                   fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round'
                                   stroke-linejoin='round' class='feather feather-database'>
                                 <ellipse cx='12' cy='5' rx='9' ry='3'></ellipse>
                                 <path d='M21 12c0 1.66-4 3-9 3s-9-1.34-9-3'></path>
                                 <path d='M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5'></path>
                              </svg>
                           </slot>
                        </div>
                        <slot name='header-title'>
                           Products of {{ service.name }}
                        </slot>
                     </h1>
                  </div>
                  <div class='col-12 col-xl-auto mb-3'>
                     <slot name='header-link' />
                  </div>
               </div>
            </div>
         </div>
      </header>
      <!-- Main page content-->
      <div class='container-fluid px-4'>
         <slot />
      </div>
   </main>
</template>

<script setup lang='ts'>
   import { Models } from '~/types';

   defineProps<{
      service: Models.Service
   }>();
</script>
