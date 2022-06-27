<template>
   <a
      v-if='checkAuth'
      ref='href'
      class='nav-link'
      :class='getClass'
      :href='target'
      @click.prevent='() => onClick()'
   >
      <div v-if='props.icon' v-html='props.icon' class='nav-link-icon'></div>
      {{ props.label }}
      <div v-if='hasSubItem' class='sidenav-collapse-arrow'>
         <i class='fas fa-angle-down'></i>
      </div>
   </a>
   <template v-if='hasSubItem'>
      <div class='collapse'
           :class='{show: subMenuActive}'
           :id='props.id'
           :data-bs-parent='`#${props.id}`'
      >
         <nav class='sidenav-menu-nested nav accordion' id='accordionSidenavPages'>
            <MenuItem
               v-for='(subItem, key) in getSubItem'
               :key='key'
               :id='`${props.id}-${key}`'
               :label='subItem.label ?? ""'
               :class='subItem.class ?? ""'
               :icon='subItem.icon ?? ""'
               :target='subItem.target ?? ""'
               :extra-matched='subItem.extraMatched ?? null'
               :items='subItem.items ?? []'
               @active='onSubMenuActive'
            />
         </nav>
      </div>
   </template>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, watchEffect } from 'vue';
   import { Inertia } from '@inertiajs/inertia';
   import { usePage } from '@inertiajs/inertia-vue3';
   import { isURL, matchedURL } from '~/utils';
   import { useAuth } from '~/uses';

   const props = defineProps({
      id: {
         type: String,
         required: true,
         default: Math.random().toString(36).substr(2, 8),
      },
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
      collapsed: hasSubItem,
      active: active.value,
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
   const checkAuth = computed(() => props.auth ? useAuth<boolean>('isLoggedIn') : true);

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

   watchEffect(() => {
      if (!href.value || !hasSubItem.value) return;

      href.value.setAttribute('data-bs-toggle', 'collapse');
      href.value.setAttribute('data-bs-target', `#${props.id}`);
      href.value.setAttribute('aria-expanded', 'false');
      href.value.setAttribute('aria-controls', props.id);
   });
</script>
