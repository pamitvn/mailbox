import { App } from 'vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import vSelect from 'vue-select';
import VueGravatar from 'vue3-gravatar';
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCircleUser, faThumbsUp, faCircleCheck, faCircleXmark } from '@fortawesome/free-regular-svg-icons';
import {
    faArrowRightToBracket,
    faBagShopping,
    faDollarSign,
    faMoneyCheckDollar,
    faListOl,
    faCartShopping,
} from '@fortawesome/free-solid-svg-icons';

// import TheTextareaField from '~/components/Form/TheTextareaField.vue';
// import TheCheckBoxField from '~/components/Form/TheCheckBoxField.vue';
// import TheSwitchField from '~/components/Form/TheSwitchField.vue';
// import TheTable from '~/components/Table/TheTable.vue';
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
    // app.component('VInput', VInput);
    // app.component('TheTextareaField', TheTextareaField);
    // app.component('TheCheckboxField', TheCheckBoxField);
    // app.component('TheSwitchField', TheSwitchField);
    app.component('TheSelect', vSelect);

    /**
     * Register Table Components
     */
    // app.component('TheTable', TheTable);

    /**
     * Register Vue Gravatar
     */
    app.use(VueGravatar);

    /**
     * Register FontAwesomeIcon
     */
    library.add(
        faCircleUser, faThumbsUp, faCircleCheck,
        faCircleXmark, faArrowRightToBracket, faDollarSign,
        faMoneyCheckDollar, faBagShopping, faListOl,
        faCartShopping,
    );
    app.component('font-awesome-icon', FontAwesomeIcon);

    return app;
}
