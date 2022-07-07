<template>
   <the-head>
      <title>Statistics</title>
   </the-head>
   <div class='container mt-5'>
      <div class='row'>
         <div class='col-xl-3 col-md-6 mb-4'>
            <!-- Dashboard info widget 1-->
            <div class='card border-start-lg border-start-primary h-100'>
               <div class='card-body'>
                  <div class='d-flex align-items-center'>
                     <div class='flex-grow-1'>
                        <div class='small fw-bold text-primary mb-1'>Orders</div>
                        <div class='h5'>{{ total_orders }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class='col-xl-3 col-md-6 mb-4'>
            <!-- Dashboard info widget 2-->
            <div class='card border-start-lg border-start-secondary h-100'>
               <div class='card-body'>
                  <div class='d-flex align-items-center'>
                     <div class='flex-grow-1'>
                        <div class='small fw-bold text-secondary mb-1'>Total Users</div>
                        <div class='h5'>{{ total_users }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class='col-xl-3 col-md-6 mb-4'>
            <!-- Dashboard info widget 3-->
            <div class='card border-start-lg border-start-success h-100'>
               <div class='card-body'>
                  <div class='d-flex align-items-center'>
                     <div class='flex-grow-1'>
                        <div class='small fw-bold text-success mb-1'>Order revenue</div>
                        <div class='h5'>{{ order_revenue }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <div class='row me-md-3'>
         <div v-for='(item, key) in date ?? []' :key='key' class='col-xl-4 col-md-6'>
            <div class='card mb-4'>
               <div class='card-header'>{{ item.label }}</div>
               <div class='card-body'>
                  <div class='d-flex justify-content-between mt-2'>
                     <span>Order revenue</span>
                     <span><b>{{ item.order_revenue }}</b></span>
                  </div>

                  <div class='d-flex justify-content-between mt-2'>
                     <span>Users</span>
                     <span><b>{{ item.total_users }}</b></span>
                  </div>
               </div>
            </div>
         </div>
      </div>

   </div>
</template>

<script>
   import { defineComponent } from 'vue';
   import dateFormat from 'dateformat';

   export default defineComponent({
      props: ['total_orders', 'total_users', 'order_revenue', 'date'],
      setup() {
         const now = new Date();
         const month = dateFormat(now, 'm/yyyy');
         return { month };
      },
   });
</script>
