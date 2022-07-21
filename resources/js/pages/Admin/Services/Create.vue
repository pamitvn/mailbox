<template>
   <the-head>
      <title>Create New Service</title>
   </the-head>
   <Layout>
      <template #header-link>
         <the-link class='btn btn-sm btn-light text-primary' :href='$route("admin.service.index")'>
            <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                 stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                 class='feather feather-arrow-left me-1'>
               <line x1='19' y1='12' x2='5' y2='12'></line>
               <polyline points='12 19 5 12 12 5'></polyline>
            </svg>
            Back to list
         </the-link>
      </template>
      <div class='card'>
         <div class='card-header'>Details</div>
         <div class='card-body'>
            <form @submit.prevent='() => onSubmitForm()' enctype='multipart/form-data'>
               <div class='mb-3'>
                  <TheInputField v-model='form.name'
                                 :error='form.errors.name'
                                 label='Name'
                                 type='text'
                                 placeholder='Enter name'
                  />
               </div>

               <div class='mb-3'>
                  <TheInputField v-model='form.lifetime'
                                 :error='form.errors.lifetime'
                                 label='Lifetime'
                                 type='text'
                                 placeholder='Enter lifetime'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field
                     :error='form.errors.feature_image'
                     label='Image'
                     type='file'
                     accept='image/*'
                     placeholder='Bank image'
                     @input='form.feature_image = $event.target.files[0]'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field v-model='form.price'
                                   :error='form.errors.price'
                                   label='Price'
                                   type='number'
                                   placeholder='Enter price'
                  />
               </div>

               <div class='mb-3'>
                  <the-input-field v-model='form.clean_after'
                                   :error='form.errors.clean_after'
                                   label='Auto Clean After (Hours)'
                                   type='number'
                                   placeholder='Enter hours'
                  />
               </div>

               <div class='mb-3'>
                  <the-switch-field v-model='form.pop3'
                                    :error='form.errors.pop3'
                                    label='Pop3'
                  />
               </div>
               <div class='mb-3'>
                  <the-switch-field v-model='form.imap'
                                    :error='form.errors.imap'
                                    label='IMAP'
                  />
               </div>

               <div class='mb-3'>
                  <the-switch-field v-model='form.visible'
                                    :error='form.errors.visible'
                                    label='Visible'
                  />
               </div>

               <div class='mb-3'>
                  <the-switch-field v-model='form.is_local'
                                    :error='form.errors.is_local'
                                    label='Product From Local'
                  />
               </div>

               <div v-for='(item, index) in getExtraFields' :key='index'>
                  <div class='mb-3'>
                     <component :is='getComponent(item)'
                                v-model='form.extras[`${item.key}`]'
                                v-bind='item.attribute'
                                :error='form.errors[`extras.${item.key}`]'
                     ></component>
                  </div>
               </div>

               <!-- Submit button-->
               <button class='btn btn-primary' type='submit'>Add</button>
            </form>
         </div>
      </div>
   </Layout>
</template>

<script setup lang='ts'>
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useRoute } from '~/utils';
   import { Models } from '~/types';

   import Layout from './Layout.vue';
   import _ from 'lodash';
   import { computed } from 'vue';

   type FormType = Omit<Models.Service, 'id' | 'slug' | 'created_at' | 'updated_at'>;

   const props = defineProps<{
      extraFields: {
         [key: string]: {
            rule: string
            show_unless?: string
            show_if?: string
            attribute: {
               [key: string]: any
            }
         }
      }
   }>();

   const form = useForm<FormType>({
      name: null,
      lifetime: null,
      feature_image: null,
      price: 0,
      pop3: false,
      imap: false,
      visible: false,
      is_local: true,
      clean_after: 4,
      extras: {},
   });

   const getExtraFields = computed(() => {
      let fields = props.extraFields;

      return _.filter(_.map(fields, (item, key) => ({ ...item, key })), (item) => {

         if (item.show_if && form[item.show_if]) return true;
         if (item.show_unless && !form[item.show_unless]) return true;

         return false;
      });
   });

   const getComponent = (item) => _.get(item, 'is', 'the-input-field');
   const onSubmitForm = () => {
      return form.post(useRoute('admin.service.store'), {
         forceFormData: true,
         onSuccess: () => {
            form.reset();
         },
      });
   };

</script>
