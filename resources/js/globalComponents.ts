import { App } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import vSelect from 'vue-select';
import VueGravatar from 'vue3-gravatar';

import TheInputField from '~/components/Form/TheInputField.vue';
import TheCheckBoxField from '~/components/Form/TheCheckBoxField.vue';
import TheTable from '~/components/Table/TheTable.vue';

import 'vue-select/dist/vue-select.css';

type VueApp = App<Element>;

export default (app: VueApp): VueApp => {
    /**
     * Register inertia components
     */
    app.component('TheHead', Head);
    app.component('TheLink', Link);

    /**
     * Register Form Components
     */
    app.component('TheInputField', TheInputField);
    app.component('TheCheckboxField', TheCheckBoxField);
    app.component('TheSelect', vSelect);

    /**
     * Register Table Components
     */
    app.component('TheTable', TheTable);

    /**
     * Register Vue Gravatar
     */
    app.use(VueGravatar);

    return app;
}
