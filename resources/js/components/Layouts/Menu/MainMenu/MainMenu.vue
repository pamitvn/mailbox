<template>
   <div class='nav accordion' id='accordionSidenav'>
      <div style='padding-top: 1.75rem'></div>
      <template v-for='(menuItem, key) in getData' :key='key'>
         <template v-if='menuItem.group'>
            <MenuGroup :name='menuItem.group' :class='menuItem.class ?? ""' />

            <template v-if='menuItem.items'>
               <MenuItem
                  v-for='(subMenuItem, subKey) in menuItem.items'
                  :key='subKey'
                  :id='`mainMenu-${key}-${subKey}`'
                  :label='subMenuItem.label ?? ""'
                  :class='subMenuItem.class ?? ""'
                  :icon='subMenuItem.icon ?? ""'
                  :target='subMenuItem.target'
                  :extra-matched='subMenuItem.extraMatched'
                  :items='subMenuItem.items ?? []'
               />
            </template>

         </template>
         <template v-else>
            <MenuItem
               :id='`mainMenu-${key}`'
               :label='menuItem.label ?? ""'
               :class='menuItem.class ?? ""'
               :icon='menuItem.icon ?? ""'
               :target='menuItem.target'
               :extra-matched='menuItem.extraMatched'
               :items='menuItem.items ?? []'
            />
         </template>
      </template>
   </div>
</template>

<script setup>
   import _ from 'lodash';
   import { computed } from 'vue';

   import MenuGroup from '~/components/Layouts/Menu/MainMenu/MenuGroup';
   import MenuItem from '~/components/Layouts/Menu/MainMenu/MenuItem';

   const props = defineProps({
      data: {
         type: Array,
         default: [],
      },
   });

   const getData = computed(() => _.orderBy(props.data, ['order'], ['asc']));
</script>
