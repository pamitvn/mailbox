import { App } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

import TheInputField from '~/components/Form/TheInputField.vue';
import TheCheckBoxField from '~/components/Form/TheCheckBoxField.vue';
import TheTable from '~/components/Table/TheTable.vue';

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

    /**
     * Register Table Components
     */
    app.component('TheTable', TheTable);

    return app;
}
