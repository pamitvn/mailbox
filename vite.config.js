import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import VueTypeImports from 'vite-plugin-vue-type-imports';

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
      VueTypeImports(),
   ],
   resolve: {
      alias: {
         '~': '/resources/app',
         '@UI': '/resources/app/@UI',
         'ziggy': '/vendor/tightenco/ziggy/dist/index.es.js',
      },
   },
   build: {
      rollupOptions: {
         output: {
            entryFileNames: 'assets/js/[name]-[hash].js',
            chunkFileNames: 'assets/chunks/[name]-[hash].js',
            assetFileNames: 'assets/css/[name]-[hash][extname]',
         },

      },
      commonjsOptions: {
         transformMixedEsModules: true,
      },
   },
});
