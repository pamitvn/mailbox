/**
 * Import Libraries
 */
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
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
// import MasterLayout from '~/layouts/MasterLayout.vue';

/**
 * Import Library Styles
 */
import './css/style.scss';
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
    resolve: async name => {
        const page = resolvePageComponent(`./pages/${name}.vue`, import.meta.glob('./pages/**/*.vue'));

        // if (page.layout === undefined && !name.startsWith('Public/')) {
        //     page.layout = MasterLayout;
        // }

        return page as any;
    },
    setup({ el, App, props, plugin }) {
        const app = globalComponents(createApp({ render: () => h(globalApp, () => h(App, props)) }));

        app.config.globalProperties.$route = useRoute;

        app.use(plugin);
        app.use(moshaToast);

        app.mount(el);
    },
}).catch((e) => console.error('App Error', e.toString()));
