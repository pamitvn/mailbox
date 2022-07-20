import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

// https://vitejs.dev/config/
export default defineConfig({
   server: {
      host: 'localhost',
   },
   define: {
      'process.env': process.env,
   },
   plugins: [
      laravel({
         input: 'resources/app/main.ts',
         refresh: true,
      }),
      vue({
         template: {
            transformAssetUrls: {
               base: null,
               includeAbsolute: false,
            },
         },
      }),
   ],
   resolve: {
      alias: {
         '~': '/resources/app',
         'ziggy': '/vendor/tightenco/ziggy/dist/index.m.js',
      },
   },
   // build: {
   //    commonjsOptions: {
   //       transformMixedEsModules: true,
   //    },
   // },
});
