<template>
   <li class='px-3 py-2 rounded-sm mb-0.5 last:mb-0' :class="activeCondition && 'bg-slate-900'">
      <slot :handleClick='handleClick' :expanded='expanded' />
   </li>
</template>

<script>
   import { ref, watch } from 'vue';
   import { useThemeStore } from '~/stores/useThemeStore';
   import { storeToRefs } from 'pinia/dist/pinia';

   export default {
      name: 'SidebarLinkGroup',
      props: ['activeCondition'],
      setup(props) {
         const useStore = useThemeStore();

         const { sidebarExpanded } = storeToRefs(useStore);
         const expanded = ref(props.activeCondition);

         const handleClick = () => {
            if (!sidebarExpanded.value) {
               useStore.$patch({
                  sidebarExpanded: true,
               });
               expanded.value = true;
            } else {
               expanded.value = !expanded.value;
            }
         };

         watch(() => props.activeCondition, val => {
            expanded.value = val;
         });

         return {
            expanded,
            handleClick,
         };
      },
   };
</script>
