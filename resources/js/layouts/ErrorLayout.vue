<template>
   <div id='layoutError'>
      <div id='layoutError_content'>
         <main>
            <slot />
         </main>
      </div>
      <div id='layoutError_footer'>
         <footer class='footer-admin mt-auto footer-light'>
            <div class='container-xl px-4'>
               <div class='row'>
                  <div class='col-md-6 small'>Copyright © 2022 CapchaTeam</div>
                  <div class='col-md-6 text-md-end small'>
                     Powered by <a class='text-primary' href='https://phamminhan.ceo'>PAM</a>
                  </div>
               </div>
            </div>
         </footer>
      </div>
   </div>
</template>

<script setup>
</script>
