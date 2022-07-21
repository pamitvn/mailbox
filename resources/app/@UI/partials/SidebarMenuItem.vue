<template>
   <template v-if='checkAuth'>
      <li v-if='!hasSubItem' class='px-3 py-2 rounded-sm mb-0.5 last:mb-0' :class="active && 'bg-slate-900'">
         <a class='block text-slate-200 hover:text-white truncate transition duration-150 cursor-pointer'
            :href='href'
            @click='() => onClick()'>
            <div class='flex items-center justify-between'>
               <div class='grow flex items-center'>
                  <span v-if='hasIcon' v-html='active ? iconActive : icon' class='shrink-0 h-6 w-6'></span>
                  <span
                     class='text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200'>
                  {{ label }}
               </span>
               </div>
               <!-- Badge -->
               <!--            <div class='flex flex-shrink-0 ml-2'>-->
               <!--               <span-->
               <!--                  class='inline-flex items-center justify-center h-5 text-xs font-medium text-white bg-indigo-500 px-2 rounded'>-->
               <!--                  4-->
               <!--               </span>-->
               <!--            </div>-->
            </div>
         </a>
      </li>
      <sidebar-link-group v-else
                          v-slot='parentLink'
                          :activeCondition='active || subMenuActive'
      >
         <a class='block text-slate-200 hover:text-white truncate transition duration-150 cursor-pointer'
            :class='getClass'
            :href='href'
            @click.prevent='() => parentLink.handleClick()'
         >
            <div class='flex items-center justify-between'>
               <div class='flex items-center'>
                  <span v-if='hasIcon' v-html='active ? iconActive : icon' class='shrink-0 h-6 w-6'></span>
                  <span
                     class='text-sm font-medium ml-3 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200'>
                  {{ label }}
               </span>
               </div>
               <!-- Icon -->
               <div class='flex shrink-0 ml-2'>
                  <svg class='w-3 h-3 shrink-0 ml-1 fill-current text-slate-400'
                       :class="parentLink.expanded && 'rotate-180'" viewBox='0 0 12 12'>
                     <path d='M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z' />
                  </svg>
               </div>
            </div>
         </a>
         <div class='lg:hidden lg:sidebar-expanded:block 2xl:block'>
            <ul class='pl-9 mt-1' :class="!parentLink.expanded && 'hidden'">
               <sidebar-menu-item
                  v-for='(item, index) in getSubItem'
                  :key='index'
                  :label='item.label ?? ""'
                  :class='item.class ?? ""'
                  :icon='item.icon ?? ""'
                  :icon-active='item.iconActive ?? ""'
                  :target='item.target'
                  :extra-matched='item.extraMatched'
                  :items='item?.items ?? []'
                  :auth='item?.auth ?? false'
                  :has-icon='false'
                  @active='onSubMenuActive'
               />
            </ul>
         </div>
      </sidebar-link-group>
   </template>
</template>


<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, watchEffect } from 'vue';
   import { Inertia } from '@inertiajs/inertia';
   import { usePage } from '@inertiajs/inertia-vue3';
   import { isURL, matchedURL } from '~/utils';
   import { useAuth } from '~/uses';
   import SidebarLinkGroup from '@UI/partials/SidebarLinkGroup.vue';

   const props = defineProps({
      label: {
         type: String,
         required: true,
         default: 'Default label',
      },
      class: {
         type: String,
         required: false,
         default: '',
      },
      icon: {
         type: String,
         required: false,
         default: '',
      },
      iconActive: {
         type: String,
         required: false,
         default: '',
      },
      target: {
         type: [String, Object],
         default: 'javascript:;',
      },
      extraMatched: {
         type: [String, null],
         default: null,
      },
      items: {
         type: Array,
         default: [],
      },
      auth: {
         type: Boolean,
         default: false,
      },
      sidebarExpanded: {
         type: Boolean,
         default: false,
      },
      hasIcon: {
         type: Boolean,
         default: true,
      },
   });
   const emit = defineEmits(['active']);

   /**
    * @type {null|HTMLElement}
    */
   const href = ref(null);
   const subMenuActive = ref(false);

   const getSubItem = computed(() => _.orderBy(props.items, ['order'], ['asc']));
   const hasSubItem = computed(() => props.items.length ?? false);
   const getClass = computed(() => ({
      [props.class]: true,
      'hover:text-slate-200': active.value,
   }));
   const active = computed(() => {
      try {
         const matched = matchedURL(new URL(usePage().url.value, window.location.origin).toString(), props.target);
         const extraMatched = new RegExp(props.extraMatched).test(usePage().url.value);

         return matched || extraMatched;
      } catch (e) {
         return false;
      }
   });
   const checkAuth = computed(() => props.auth ? useAuth('isLoggedIn') : true);

   const onClick = () => {
      if (hasSubItem.value) return;

      if (!isURL(props.target)) return Inertia.get(props.target);

      const targetURL = new URL(props.target);
      const selfHost = location.host;

      if (targetURL.host === selfHost) return Inertia.get(props.target);

      window.open(
         targetURL.toString(),
         '_blank',
      );
   };

   const onSubMenuActive = () => {
      subMenuActive.value = true;
   };

   watchEffect(() => {
      if (!active.value) {
         subMenuActive.value = false;
         return;
      }

      emit('active');
   });
</script>
