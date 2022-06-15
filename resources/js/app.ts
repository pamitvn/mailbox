/**
 * Import Libraries
 */
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import moshaToast from 'mosha-vue-toastify';

/**
 * Import Local
 */
import { author, useRoute } from '~/utils';
import globalComponents from '~/globalComponents';

/**
 * Import Local Components
 */
import globalApp from '~/App.vue';
import MasterLayout from '~/layouts/MasterLayout.vue';

/**
 * Import Library Styles
 */
import 'mosha-vue-toastify/dist/style.css';

/**
 * Initial Inertia Progress
 */
InertiaProgress.init();

/**
 * Initial Author
 */
author();

createInertiaApp({
    title: title => title ? title : 'MailBox',
    resolve: name => {
        const page = require(`./pages/${name}`).default;
        if (page.layout === undefined && !name.startsWith('Public/')) {
            page.layout = MasterLayout;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = globalComponents(createApp({ render: () => h(globalApp, () => h(App, props)) }));

        app.config.globalProperties.$route = useRoute;

        app.use(plugin);
        app.use(moshaToast);

        app.mount(el);
    },
}).catch((e) => console.error('App Error', e.toString()));
