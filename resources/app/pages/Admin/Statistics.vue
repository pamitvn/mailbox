<template>
   <the-head>
      <title>Statistics</title>
   </the-head>
   <dl class='mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3'>
      <div v-for='item in stats' :key='item.id'
           class='relative bg-white px-4 pt-6 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden'>
         <dt>
            <div class='absolute bg-indigo-500 rounded-md p-3'>
               <font-awesome-icon :icon='item.icon' class='h-6 w-6 text-white' aria-hidden='true' />
            </div>
            <p class='ml-16 text-sm font-medium text-gray-500 truncate'>{{ item.name }}</p>
         </dt>
         <dd class='ml-16 pb-6 flex items-baseline sm:pb-7'>
            <p class='oldstyle-nums text-2xl font-semibold text-gray-900'>
               {{ item.stat }}
            </p>
         </dd>
      </div>
   </dl>

   <div class='flex flex-col space-y-6 mt-6'>
      <div class='bg-white w-full px-6 py-4 rounded-lg'>
         <h3 class='text-xl'>Statistic by Users</h3>
         <div ref='userChart' style='height: 300px'></div>
      </div>
      <div class='bg-white w-full px-6 py-4 rounded-lg'>
         <h3 class='text-xl'>Statistic by Order</h3>
         <div ref='orderChart' style='height: 300px'></div>
      </div>
      <div class='bg-white w-full px-6 py-4 rounded-lg'>
         <h3 class='text-xl'>Statistic by Order revenues</h3>
         <div ref='orderRevenueChart' style='height: 300px'></div>
      </div>
   </div>

</template>

<script lang='ts'>
   import { defineComponent, onMounted, ref } from 'vue';
   import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
   import dateFormat from 'dateformat';

   export default defineComponent({
      props: ['total_orders', 'total_users', 'order_revenue'],
      setup(props) {
         const userChart = ref(null);
         const orderChart = ref(null);
         const orderRevenueChart = ref(null);

         const stats = [
            { id: 1, name: 'Orders', stat: props.total_orders, icon: 'fa-solid fa-bag-shopping' },
            { id: 2, name: 'Total Users', stat: props.total_users, icon: 'fa-solid fa-user-plus' },
            { id: 3, name: 'Order revenue', stat: props.order_revenue, icon: 'fa-solid fa-dollar-sign' },
         ];

         const getMonthYear = dateFormat(Date.now(), 'm/yyyy');

         onMounted(() => {
            new Chartisan({
               el: userChart.value,
               url: '/api/chart/statistic_user_chart',
               hooks: new ChartisanHooks()
                  .colors()
                  .beginAtZero()
                  .datasets('bar')
                  .responsive(true),
               options: {},
            });

            new Chartisan({
               el: orderChart.value,
               url: '/api/chart/statistic_order_chart',
               hooks: new ChartisanHooks()
                  .colors()
                  .beginAtZero()
                  .datasets('bar')
                  .responsive(true),
               options: {},
            });

            new Chartisan({
               el: orderRevenueChart.value,
               url: '/api/chart/statistic_order_revenue_chart',
               hooks: new ChartisanHooks()
                  .colors()
                  .beginAtZero()
                  .datasets('bar')
                  .responsive(true),
               options: {},
            });
         });

         return { orderChart, orderRevenueChart, userChart, stats, getMonthYear };
      },
   });

</script>
