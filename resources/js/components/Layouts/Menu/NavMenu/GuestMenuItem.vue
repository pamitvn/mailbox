<template>
   <a class='btn btn-icon btn-transparent-dark dropdown-toggle' id='navbarDropdownUserImage'
      href='javascript:void(0);' role='button' data-bs-toggle='dropdown' aria-haspopup='true'
      aria-expanded='false'>
      <vue-gravatar class='img-fluid' email='' default-img='mp' />
   </a>
   <div class='dropdown-menu dropdown-menu-end border-0 shadow animated--fade-in-up'
        aria-labelledby='navbarDropdownUserImage'>
      <the-link class='dropdown-item' :href='$route("login")'>
         <div class='dropdown-item-icon'><i data-feather='log-in'></i></div>
         Login
      </the-link>

      <the-link class='dropdown-item' :href='$route("register")'>
         <div class='dropdown-item-icon'><i data-feather='user-plus'></i></div>
         Register
      </the-link>
   </div>
</template>

<script>
   export default {
      name: 'GuestMenuItem',
   };
</script>
