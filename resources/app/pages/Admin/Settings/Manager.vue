<template>
   <the-head>
      <title>{{ getPageTitle }} - Settings</title>
   </the-head>

   <!-- Panel body -->
   <div class='p-6 space-y-6'>
      <h2 class='text-2xl text-slate-800 font-bold mb-5'>{{ getPageTitle }}</h2>

      <section>
         <v-dynamic-form ref='dynamicFormRef' v-model='payload' @submit='handleSubmitForm' />
      </section>
   </div>
   <!-- Panel footer -->
   <footer>
      <div class='flex flex-col px-6 py-5 border-t border-slate-200'>
         <div class='flex self-end'>
            <v-button type='button'
                      variant='primary'
                      :loading='dynamicFormRef?.form?.processing'
                      @click='() => onSubmitForm()'
            >
               Save Changes
            </v-button>
         </div>
      </div>
   </footer>
</template>

<script setup lang='ts'>
   import { computed } from 'vue';
   import { useDynamicForm } from '~/uses';
   import { useRoute } from '~/utils';

   import type Form from '~/types/Components/Form';

   import VDynamicForm from '~/components/VDynamicForm.vue';
   import VButton from '~/components/VButton.vue';

   const props = defineProps<{
      group: {
         label: string
         key: string
         define: string
         fields: any[]
      }
      settings: object
   }>();

   const getPageTitle = computed(() => props.group.label);

   const {
      dynamicFormRef,
      payload,
      onSubmitForm,
   } = useDynamicForm(
      props.group?.fields || [] as any,
      props.settings || {} as any);

   const handleSubmitForm: Form.DynamicForm.HandleSubmitForm = (form, values, resetForm) => {
      return form.post(useRoute('admin.setting', { setting: props.group.key }));
   };
</script>
