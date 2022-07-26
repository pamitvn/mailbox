<template>
   <the-head>
      <title>Statistics</title>
   </the-head>
   <dl class='mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3'>
      <v-simple-stat-card v-for='item in stats' :key='item.id'
                          :label='item.name'
                          :stat='item.stat'>
         <template #icon>
            <font-awesome-icon :icon='item.icon' class='h-6 w-6 text-white' aria-hidden='true' />
         </template>
      </v-simple-stat-card>
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

<script setup lang='ts'>
   import { onMounted, ref } from 'vue';
   import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
   import VSimpleStatCard from '~/components/VSimpleStatCard.vue';

   const props = defineProps<{
      total_orders?: string | number
      total_users?: string | number
      order_revenue?: string | number
   }>();

   const userChart = ref(null);
   const orderChart = ref(null);
   const orderRevenueChart = ref(null);

   const stats = [
      { id: 1, name: 'Orders', stat: props.total_orders, icon: 'fa-solid fa-bag-shopping' },
      { id: 2, name: 'Total Users', stat: props.total_users, icon: 'fa-solid fa-user-plus' },
      { id: 3, name: 'Order revenue', stat: props.order_revenue, icon: 'fa-solid fa-dollar-sign' },
   ];

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

</script>
