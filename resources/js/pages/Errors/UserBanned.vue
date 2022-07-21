<template>
   <div class='container-xl px-4'>
      <div class='row justify-content-center'>
         <div class='col-lg-6'>
            <div class=' mt-4'>
               <img class='img-fluid p-4' src='/assets/img/illustrations/403-error-forbidden.svg' alt=''>

               <h1 class='text-xl-center text-break text-red mb-3'>Suspended Account</h1>

               <p v-if='blacklisted?.by_user?.name' class='lead'>
                  <span class='text-primary'>{{ blacklisted.by_user.name }}</span> has suspended your account.
               </p>
               <p class='lead'>The explanation is "<span class='text-warning'>{{ blacklisted.reason }}</span>".</p>
               <p v-if='blacklisted.duration' class='lead'>
                  On <span class='text-success'>{{ durationDay }}</span> at <span class='text-success'>{{ durationTime
                  }}</span>, your account will once more be unblocked.
               </p>
               <p v-else class='lead'>
                  Your account has been permanently <span class='text-success'>suspended</span>.
               </p>
            </div>
         </div>
      </div>
   </div>
</template>

<script lang='ts'>
   import { Models } from '~/types';
   import ErrorLayout from '~/layouts/ErrorLayout.vue';
   import { defineComponent } from 'vue';
   import dateFormat from 'dateformat';

   export default defineComponent({
      layout: ErrorLayout,
      props: {
         user: Models.User,
         blacklisted: Models.Blacklisted,
      },
      computed: {
         durationDay() {
            return dateFormat(this.$props?.blacklisted?.duration, 'mmmm dS, yyyy');
         },
         durationTime() {
            return dateFormat(this.$props?.blacklisted?.duration, 'h:MM:ss TT');
         },
      },
   });
</script>
