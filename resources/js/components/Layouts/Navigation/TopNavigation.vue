<template>
   <nav
      class='topnav navbar navbar-expand shadow justify-content-between justify-content-sm-start navbar-light bg-white'
      id='sidenavAccordion'>
      <!-- Sidenav Toggle Button-->
      <button class='btn btn-icon btn-transparent-dark order-1 order-lg-0 me-2 ms-lg-2 me-lg-0' id='sidebarToggle'>
         <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
              stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
              class='feather feather-menu'>
            <line x1='3' y1='12' x2='21' y2='12'></line>
            <line x1='3' y1='6' x2='21' y2='6'></line>
            <line x1='3' y1='18' x2='21' y2='18'></line>
         </svg>
      </button>
      <!-- Navbar Brand-->
      <!-- * * Tip * * You can use text or an image for your navbar brand.-->
      <!-- * * * * * * When using an image, we recommend the SVG format.-->
      <!-- * * * * * * Dimensions: Maximum height: 32px, maximum width: 240px-->
      <LogoNav />
      <!-- Navbar Items-->
      <NavMenu />
   </nav>
</template>

<script setup>
   import LogoNav from '~/components/Layouts/Navigation/LogoNav';
   import NavMenu from '~/components/Layouts/Menu/NavMenu/NavMenu';
</script>
