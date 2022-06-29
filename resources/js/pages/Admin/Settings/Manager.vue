<template>
   <the-head>
      <title>Setting Managers</title>
   </the-head>
   <Layout :menu='groups'>
      <div class='card mb-4'>
         <div class='card-header'>{{ group.label }} Settings</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()'>

               <div v-for='(item, key) in group.fields'>
                  <div class='mb-3'>
                     <component :is='getComponent(item)'
                                v-model='form[key]'
                                v-bind='item.attribute'
                                :error='form.errors[key]'
                     ></component>
                  </div>
               </div>

               <!-- Save changes button-->
               <button class='btn btn-primary' type='submit'>Save changes</button>
            </form>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { watchEffect } from 'vue';
   import { useRoute } from '~/utils';
   import { useForm } from '@inertiajs/inertia-vue3';

   import Layout from './Layout.vue';

   const props = defineProps<{
      groups: Array<{
         label: string
         key: string
      }>
      group: {
         label: string
         key: string
         define: string
         fields: any[]
      }
      settings: object
   }>();

   const form = useForm(props.settings);

   const getComponent = (item) => _.get(item, 'is', 'the-input-field');
   const onSubmitForm = () => {
      return form.post(useRoute('admin.setting', { setting: props.group.key }));
   };

   watchEffect(() => {
      if (props.settings) {
         _.forEach(_.keys(props.settings), key => {
            form[key] = props.settings[key];
         });
      }
   });
</script>
