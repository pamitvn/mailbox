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
                                   stroke-linejoin='round' class='feather feather-dollar-sign'>
                                 <line x1='12' y1='1' x2='12' y2='23'></line>
                                 <path d='M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'></path>
                              </svg>
                           </slot>
                        </div>
                        <slot name='header-title'>
                           Bank List
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
