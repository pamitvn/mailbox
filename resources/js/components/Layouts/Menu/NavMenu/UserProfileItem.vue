<template>
   <a class='btn btn-icon btn-transparent-dark dropdown-toggle' id='navbarDropdownUserImage'
      href='javascript:void(0);' role='button' data-bs-toggle='dropdown' aria-haspopup='true'
      aria-expanded='false'><img class='img-fluid' src='/assets/img/illustrations/profiles/profile-1.png' /></a>
   <div class='dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up'
        aria-labelledby='navbarDropdownUserImage'>
      <h6 v-if='user' class='dropdown-header d-flex align-items-center'>
         <img class='dropdown-user-img' src='/assets/img/illustrations/profiles/profile-1.png' />
         <div class='dropdown-user-details'>
            <div class='dropdown-user-details-name'>{{ user.name }}</div>
            <div class='dropdown-user-details-email'>{{ user.email }}</div>
         </div>
      </h6>
      <div class='dropdown-divider'></div>
      <the-link class='dropdown-item' :href='$route("account.profile")'>
         <div class='dropdown-item-icon'><i data-feather='settings'></i></div>
         Account
      </the-link>
      <the-link
         as='button'
         :href='$route("logout")'
         method='POST'
         class='dropdown-item'
      >
         <div class='dropdown-item-icon'><i data-feather='log-out'></i></div>
         Logout
      </the-link>
   </div>
</template>

<script setup lang='ts'>
   import { computed } from 'vue';
   import { Models } from '~/types';
   import { useAuth } from '~/uses';

   const user = computed(() => useAuth<Models.User>('user'));
</script>
