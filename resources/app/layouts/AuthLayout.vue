<template>
   <main class='bg-white'>

      <div class='relative flex'>

         <!-- Content -->
         <div class='w-full md:w-1/2'>
            <div
               class='min-h-screen h-full flex flex-col after:flex-1 text-white bg-gradient-to-b from-cyan-500 to-blue-500 md:text-black md:from-white md:to-white'>

               <div class='flex-1'>
                  <div class='flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8'>
                     <!-- Logo -->
                     <the-link href='/'>
                        <img class='ml-2 md:ml-4 lg:ml-0 w-52 md:w-60 h-full' :src='Logo' alt='MailBox'>
                     </the-link>
                  </div>
               </div>

               <div class='max-w-md mx-auto px-4 py-8'>
                  <transition name='fade' mode='out-in' appear>
                     <main :key='$page.url'>
                        <slot />
                     </main>
                  </transition>
               </div>
            </div>
         </div>

         <!-- Image -->
         <div class='hidden md:block absolute top-0 bottom-0 right-0 md:w-1/2' aria-hidden='true'>
            <div
               class='w-full h-full text-white bg-gradient-to-b from-cyan-500 to-blue-500'>
               <div class='flex justify-center items-center h-full mx-12'>
                  <div class='flex flex-col justify-center h-full'>
                     <h1 class='text-4xl'>Why Choose us?</h1>

                     <div class='mt-12 flex flex-col space-y-6'>
                        <div v-for='i in 6' :key='i' class='flex space-x-4'>
                           <font-awesome-icon class='shrink w-6 h-6' icon='fa-regular fa-thumbs-up' />
                           <div class='flex-1'>
                              <h4 class='text-2xl'>Cheapest price on the market</h4>
                              <p class='text-sm'>Where to sell cheap - we will sell cheaper</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <img class='absolute top-1/4 left-0 -translate-x-1/2 ml-8 hidden lg:block'
                 :src='AuthDecoration' width='218' height='224' alt='Authentication decoration' />
            <!--            <div class='absolute top-1/4 left-0 -translate-x-1/2 ml-8 hidden lg:block'>-->
            <!--               <img-->
            <!--                  src='https://hotmailbox.me/assets/images/im22.png'-->
            <!--                  alt='Authentication decoration' style='max-height: 45vh;' />-->
            <!--            </div>-->
         </div>
      </div>

   </main>
</template>

<script setup lang='ts'>
   import { AuthDecoration, Logo } from '~/images';</script>
