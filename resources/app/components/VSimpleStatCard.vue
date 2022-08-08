<template>
   <div class='relative bg-white px-4 pt-6 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden'>
      <dt>
         <div class='absolute bg-indigo-500 rounded-md p-3'>
            <slot name='icon'></slot>
         </div>
         <p class='ml-16 text-sm font-medium text-gray-500 truncate'>{{ label }}</p>
      </dt>
      <dd class='ml-16 pb-6 flex items-baseline sm:pb-7'>
         <p class='text-2xl font-semibold text-gray-900'>
            {{ stat }}
         </p>
      </dd>
   </div>
</template>

<script setup lang='ts'>
   defineProps<{
      label: string
      stat: string | number
   }>();
</script>
