<template>
   <teleport to='body'>
      <v-modal v-model:open='open'
               :title='`Manager permission for service #${service?.id}`'
               max-width='xl'
      >
         <div class='p-6 mb-3'>
            <form @submit.prevent='() => onSubmitForm()'>
               <div class='grid grid-col gap-2 mt-3'>
                  <div>
                     <v-switch v-model='form.enable'
                               :error='form.errors.enable'
                               class='w-full'
                               label='Enable Permission'
                               required
                     />
                  </div>

                  <transition
                     enter-active-class='transition ease-out duration-300 transform'
                     enter-from-class='opacity-0 -translate-y-2'
                     enter-to-class='opacity-100 translate-y-0'
                     leave-active-class='transition ease-out duration-300'
                     leave-from-class='opacity-100'
                     leave-to-class='opacity-0'
                  >
                     <div v-if='!loading && form.enable' class='grid grid-col gap-2 mt-3'>
                        <div>
                           <v-input
                              :model-value='service?.name'
                              type='text'
                              label='Service'
                              disabled
                           />
                        </div>

                        <div>
                           <v-user-selector v-model='form.users'
                                            :error='form.errors.users'
                                            :options='userOptions'
                                            label='Users'
                                            multiple
                           />
                        </div>
                     </div>

                  </transition>

                  <div class='mt-6 flex justify-center'>
                     <v-button :loading='loading || form.processing'
                               type='submit'
                               variant='primary'
                               outline
                     >
                        Save change
                     </v-button>
                  </div>
               </div>
            </form>
         </div>
      </v-modal>
   </teleport>
</template>

<script setup lang='ts'>
   import _ from 'lodash';
   import { computed, ref, watchEffect } from 'vue';
   import { useForm } from '@inertiajs/inertia-vue3';
   import { useModal } from '~/uses';
   import { parseToBoolean, useRoute } from '~/utils';

   import type { Models } from '~/types/Models';

   import VModal from '~/components/VModal.vue';
   import VSwitch from '~/components/Form/VSwitch.vue';
   import VInput from '~/components/Form/VInput.vue';
   import VUserSelector from '~/components/Selectors/VUserSelector.vue';
   import VButton from '~/components/VButton.vue';
   import axios from 'axios';

   const props = defineProps<{
      service?: Models.Service
   }>();
   const emit = defineEmits(['update:service']);

   const loading = ref(false);
   const userOptions = ref([]);

   const permissionRoute = computed(() => useRoute('admin.service.permission', { service: props.service?.id }));

   const form = useForm({
      enable: false,
      users: [],
   });

   const { open, show, close } = useModal({
      onClose: () => {
         emit('update:service', null);
         form.reset();
      },
      onShow: async () => {
         if (!form.enable) return;

         try {
            loading.value = true;

            const { data } = await axios.get(permissionRoute.value);

            if (!data || !data.length) {
               return;
            }

            form.users = _.map(data, 'id');
            userOptions.value = data;
         } catch (e) {
            console.error(e);
         } finally {
            setTimeout(() => loading.value = false, 200);
         }
      },
   });

   const onSubmitForm = () => {
      return form.post(permissionRoute.value, {
         onSuccess: () => {
            close();
         },
      });
   };

   watchEffect(() => {
      props.service
         ? show()
         : close();
   });

   watchEffect(() => {
      const enable = parseToBoolean(_.get(props.service?.extras, 'permission', false));

      if (!_.isBoolean(enable)) return;

      form.enable = enable;
   });
</script>

