<template>
   <div class='container-xl px-4'>
      <div class='row justify-content-center'>
         <div class='col-lg-6'>
            <div class='text-center mt-4'>
               <img class='img-fluid p-4' src='/assets/img/illustrations/404-error-with-a-cute-animal.svg' alt=''>
               <p class='lead'>This requested URL was not found on this server.</p>
               <the-link class='text-arrow-icon' href='/'>
                  <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                       fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round'
                       stroke-linejoin='round' class='feather feather-arrow-left ms-0 me-1'>
                     <line x1='19' y1='12' x2='5' y2='12'></line>
                     <polyline points='12 19 5 12 12 5'></polyline>
                  </svg>
                  Return to Home
               </the-link>
            </div>
         </div>
      </div>
   </div>
</template>

<script setup>
   import { Link } from '@inertiajs/inertia-vue3';
</script>
