<template>
   <div class='card w-full'>
      <h1 class='text-3xl md:text-slate-800 font-bold mb-6'>Login Account! ✨</h1>

      <form>
         <div class='space-y-4'>
            <div>
               <label class='block text-sm font-medium mb-1' for='email'>Email Address</label>
               <input id='email' class='form-input w-full' type='email' />
            </div>
            <div>
               <label class='block text-sm font-medium mb-1' for='password'>Password</label>
               <input id='password' class='form-input w-full' type='password' autoComplete='on' />
            </div>
         </div>
         <div class='flex items-center justify-between mt-6'>
            <div class='mr-1'>
               <the-link class='text-sm underline hover:no-underline' :href='$route("password.request")'>Forgot
                  Password?
               </the-link>
            </div>
            <button type='submit' class='btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3'>Sign In</button>
         </div>
      </form>
      <!-- Footer -->
      <div class='pt-5 mt-6 border-t border-slate-200'>
         <div class='text-sm'>
            Don’t you have an account?
            <the-link class='font-medium text-indigo-500 hover:text-indigo-600' :href='$route("register")'>Sign Up
            </the-link>
         </div>
      </div>
   </div>
</template>

<script setup lang='ts'>

</script>

<style lang='scss' scoped>
   .card {
      @apply m-2 py-6 px-6 rounded-lg bg-white text-black;
   }

   @screen md {
      .card {
         @apply m-0 p-0
      }
   }
</style>
