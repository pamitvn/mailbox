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
                                   stroke-linejoin='round' class='feather feather-server'>
                                 <rect x='2' y='2' width='20' height='8' rx='2' ry='2'></rect>
                                 <rect x='2' y='14' width='20' height='8' rx='2' ry='2'></rect>
                                 <line x1='6' y1='6' x2='6.01' y2='6'></line>
                                 <line x1='6' y1='18' x2='6.01' y2='18'></line>
                              </svg>
                           </slot>
                        </div>
                        <slot name='header-title'>
                           Services
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
