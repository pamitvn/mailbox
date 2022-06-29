<template>
   <!-- Main page content-->
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
      <!--      <div class='row'>-->
      <!--         <div class='col-lg-6 col-xl-3 mb-4'>-->
      <!--            <div class='card bg-primary text-white h-100'>-->
      <!--               <div class='card-body'>-->
      <!--                  <div class='d-flex justify-content-between align-items-center'>-->
      <!--                     <div class='me-3'>-->
      <!--                        <div class='text-white-75 small'>Balance</div>-->
      <!--                        <div class='text-lg fw-bold'>{{ balance }}</div>-->
      <!--                     </div>-->
      <!--                     <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'-->
      <!--                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'-->
      <!--                          class='feather feather-dollar-sign feather-xl text-white-50'>-->
      <!--                        <line x1='12' y1='1' x2='12' y2='23'></line>-->
      <!--                        <path d='M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'></path>-->
      <!--                     </svg>-->
      <!--                  </div>-->
      <!--               </div>-->
      <!--            </div>-->
      <!--         </div>-->
      <!--         <div class='col-lg-6 col-xl-3 mb-4'>-->
      <!--            <div class='card bg-warning text-white h-100'>-->
      <!--               <div class='card-body'>-->
      <!--                  <div class='d-flex justify-content-between align-items-center'>-->
      <!--                     <div class='me-3'>-->
      <!--                        <div class='text-white-75 small'>Spending</div>-->
      <!--                        <div class='text-lg fw-bold'>{{ spending }}</div>-->
      <!--                     </div>-->
      <!--                     <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'-->
      <!--                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'-->
      <!--                          class='feather feather-dollar-sign feather-xl text-white-50'>-->
      <!--                        <line x1='12' y1='1' x2='12' y2='23'></line>-->
      <!--                        <path d='M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6'></path>-->
      <!--                     </svg>-->
      <!--                  </div>-->
      <!--               </div>-->
      <!--            </div>-->
      <!--         </div>-->
      <!--         <div class='col-lg-6 col-xl-3 mb-4'>-->
      <!--            <div class='card bg-success text-white h-100'>-->
      <!--               <div class='card-body'>-->
      <!--                  <div class='d-flex justify-content-between align-items-center'>-->
      <!--                     <div class='me-3'>-->
      <!--                        <div class='text-white-75 small'>Order</div>-->
      <!--                        <div class='text-lg fw-bold'>{{ order }}</div>-->
      <!--                     </div>-->
      <!--                     <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'-->
      <!--                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'-->
      <!--                          class='feather feather-check-square feather-xl text-white-50'>-->
      <!--                        <polyline points='9 11 12 14 22 4'></polyline>-->
      <!--                        <path d='M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11'></path>-->
      <!--                     </svg>-->
      <!--                  </div>-->
      <!--               </div>-->
      <!--            </div>-->
      <!--         </div>-->
      <!--         <div class='col-lg-6 col-xl-3 mb-4'>-->
      <!--            <div class='card bg-danger text-white h-100'>-->
      <!--               <div class='card-body'>-->
      <!--                  <div class='d-flex justify-content-between align-items-center'>-->
      <!--                     <div class='me-3'>-->
      <!--                        <div class='text-white-75 small'>Order Refund</div>-->
      <!--                        <div class='text-lg fw-bold'>{{ order_refund }}</div>-->
      <!--                     </div>-->
      <!--                     <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'-->
      <!--                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'-->
      <!--                          class='feather feather-message-circle feather-xl text-white-50'>-->
      <!--                        <path-->
      <!--                           d='M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z'></path>-->
      <!--                     </svg>-->
      <!--                  </div>-->
      <!--               </div>-->
      <!--            </div>-->
      <!--         </div>-->

      <!--         <div class='col-lg-6 col-xl-3 mb-4'>-->
      <!--            <div class='card bg-cyan text-white h-100'>-->
      <!--               <div class='card-body'>-->
      <!--                  <div class='d-flex justify-content-between align-items-center'>-->
      <!--                     <div class='me-3'>-->
      <!--                        <div class='text-white-75 small'>Order Failed</div>-->
      <!--                        <div class='text-lg fw-bold'>{{ order_failed }}</div>-->
      <!--                     </div>-->
      <!--                     <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'-->
      <!--                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'-->
      <!--                          class='feather feather-message-circle feather-xl text-white-50'>-->
      <!--                        <path-->
      <!--                           d='M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z'></path>-->
      <!--                     </svg>-->
      <!--                  </div>-->
      <!--               </div>-->
      <!--            </div>-->
      <!--         </div>-->
      <!--      </div>-->

      <!--      <div class='card card-header-actions h-100 mb-4'>-->
      <!--         <div class='card-header'>-->
      <!--            Spending in the last 24 hours-->
      <!--         </div>-->
      <!--         <div class='card-body'>-->
      <!--            <div ref='spendingChart' style='height: 300px'></div>-->
      <!--         </div>-->
      <!--      </div>-->

      <!--      <div class='card card-header-actions h-100'>-->
      <!--         <div class='card-header'>-->
      <!--            Order in the last 24 hours-->
      <!--         </div>-->
      <!--         <div class='card-body'>-->
      <!--            <div ref='orderChart' style='height: 300px'></div>-->
      <!--         </div>-->
      <!--      </div>-->

   </div>

</template>

<script lang='ts'>
   import _ from 'lodash';
   import { computed, defineComponent, ref } from 'vue';
   import { usePage } from '@inertiajs/inertia-vue3';

   export default defineComponent({
      props: ['balance', 'spending', 'order', 'order_refund', 'order_failed'],
      setup() {
         const spendingChart = ref(null);
         const orderChart = ref(null);

         const notification = computed(() => _.get(usePage().props.value, 'notification', {
            enable: false,
         }));

         // onMounted(() => {
         //    new Chartisan({
         //       el: orderChart.value,
         //       url: '/api/chart/user_order_chart',
         //       hooks: new ChartisanHooks()
         //          .colors()
         //          .beginAtZero()
         //          .datasets('bar')
         //          .responsive(true),
         //       options: {},
         //    });
         //
         //    new Chartisan({
         //       el: spendingChart.value,
         //       url: '/api/chart/user_spending_chart',
         //       hooks: new ChartisanHooks()
         //          .colors()
         //          .beginAtZero()
         //          .datasets('bar')
         //          .responsive(true),
         //       options: {},
         //    });
         // });

         return { spendingChart, orderChart, notification };
      },
   });

</script>
