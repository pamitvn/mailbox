<template>
   <div class='container-xl px-4'>
      <div class='d-flex justify-content-center'>
         <!--         <span>Copyright &copy; {{ y }} CapchaTeam.</span>-->
         &nbsp;
         <span>Powered by <a href='https://phamminhan.ceo' target='_blank'>PAM</a></span>
      </div>
   </div>
</template>

<script>
   import dateFormat from 'dateformat';

   export default {
      name: 'TheFooter',
      setup() {
         const y = dateFormat(new Date(), 'yyyy');
         return { y };
      },
   };
</script>

