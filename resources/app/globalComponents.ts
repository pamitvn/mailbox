import { App } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import VueSelect from 'vue-select';
// @ts-ignore
import VueGravatar from 'vue3-gravatar';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import '~/libs/fontawesome';

import VInput from '~/components/Form/VInput.vue';
import VSelect from '~/components/Form/VSelect.vue';
import VSelectDropdown from '~/components/Form/VSelectDropdown.vue';
import VTextarea from '~/components/Form/VTextarea.vue';
// import TheCheckBoxField from '~/components/Form/TheCheckBoxField.vue';
import VSwitch from '~/components/Form/VSwitch.vue';
import VTinyRichText from '~/components/Form/VTinyRichText.vue';

import 'vue-select/dist/vue-select.css';

type VueApp = App<Element>;

export default (app: VueApp): VueApp => {
    /**
     * Register Directives
     */

    /**
     * Register inertia components
     */
    app.component('TheHead', Head);
    app.component('TheLink', Link);

    /**
     * Register Form Components
     */
    app.component('VInput', VInput);
    app.component('VSelect', VSelect);
    app.component('VSelectDropdown', VSelectDropdown);
    app.component('VTextarea', VTextarea);
    // app.component('TheCheckboxField', TheCheckBoxField);
    app.component('VSwitch', VSwitch);
    app.component('VTinyRichText', VTinyRichText);
    app.component('VueSelect', VueSelect);

    /**
     * Register Vue Gravatar
     */
    app.use(VueGravatar);

    /**
     * Register FontAwesomeIcon
     */
    app.component('font-awesome-icon', FontAwesomeIcon);

    return app;
}
