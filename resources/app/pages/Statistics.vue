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

   <div class='bg-white px-6 py-4 rounded-lg mt-6'>
      <div ref='spendingChart' style='height: 300px'></div>
   </div>

</template>

<script lang='ts'>
   import { defineComponent, onMounted, ref } from 'vue';
   import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';


   export default defineComponent({
      props: ['balance', 'spending', 'order'],
      setup(props) {
         const spendingChart = ref(null);
         const orderChart = ref(null);

         const stats = [
            { id: 1, name: 'Balance', stat: props.balance, icon: 'fa-solid fa-dollar-sign' },
            { id: 2, name: 'Spending', stat: props.spending, icon: 'fa-solid fa-money-check-dollar' },
            { id: 3, name: 'Order', stat: props.order, icon: 'fa-solid fa-bag-shopping' },
         ];

         onMounted(() => {
            new Chartisan({
               el: spendingChart.value,
               url: '/api/chart/user_spending_chart',
               hooks: new ChartisanHooks()
                  .colors()
                  .beginAtZero()
                  .datasets('bar')
                  .responsive(true),
               options: {},
            });
         });

         return { spendingChart, orderChart, stats };
      },
   });

</script>
