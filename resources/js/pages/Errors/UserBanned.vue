<template>
   <div class='container-xl px-4'>
      <div class='row justify-content-center'>
         <div class='col-lg-6'>
            <div class='text-center mt-4'>
               <img class='img-fluid p-4' src='/assets/img/illustrations/403-error-forbidden.svg' alt=''>
               <p class='lead'><span class='text-primary'>{{ user.name }}</span> has placed a ban on you.</p>
               <p class='lead'>"<span class='text-red'>{{ blacklisted.reason }}</span>" is the reason.</p>
               <p class='lead'><span class='text-success'>{{ blacklisted.duration }}</span> is the end of the ban
                  period.</p>
            </div>
         </div>
      </div>
   </div>
</template>

<script lang='ts'>
   import { Models } from '~/types';
   import ErrorLayout from '~/layouts/ErrorLayout.vue';
   import { defineComponent } from 'vue';

   export default defineComponent({
      layout: ErrorLayout,
      props: {
         user: Models.User,
         blacklisted: Models.Blacklisted,
      },
   });
</script>
