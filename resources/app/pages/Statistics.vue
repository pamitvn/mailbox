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

   <div class='bg-white px-6 py-4 rounded-lg mt-6'>
      <div ref='spendingChart' style='height: 300px'></div>
   </div>

</template>

<script setup lang='ts'>
   import { onMounted, ref } from 'vue';
   import { Chartisan, ChartisanHooks } from '@chartisan/chartjs';
   import VSimpleStatCard from '~/components/VSimpleStatCard.vue';

   const props = defineProps<{
      balance?: string | number
      spending?: string | number
      order?: string | number
   }>();

   const spendingChart = ref(null);

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

</script>
