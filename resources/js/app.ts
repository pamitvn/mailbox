import './bootstrap';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import moshaToast from 'mosha-vue-toastify';

import { author, useRoute } from '~/utils';
import globalApp from '~/App.vue';
import MasterLayout from '~/layouts/MasterLayout.vue';

import 'mosha-vue-toastify/dist/style.css';

InertiaProgress.init();

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
        const app = createApp({ render: () => h(globalApp, () => h(App, props)) })
            .use(plugin);
        app.use(moshaToast);
        app.config.globalProperties.$route = useRoute;
        app.mount(el);
    },
}).catch((e) => console.error('App Error', e.toString()));
