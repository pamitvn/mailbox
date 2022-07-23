import { defineLayoutFor } from '~/utils';

import DefaultLayout from '~/layouts/DefaultLayout.vue';
import AuthLayout from '~/layouts/AuthLayout.vue';
import AccountLayout from '~/layouts/AccountLayout.vue';

const defineDefaultLayout = defineLayoutFor(DefaultLayout, [
    'Home',
    'Statistics',
]);

const defineAuthLayouts = defineLayoutFor(AuthLayout, [
    'LoginPage',
    'RegisterPage',
    [
        'Password',
        [
            'ForgotPage',
            'ResetPage',
        ],
    ],
], [
    'Auth',
]);

const defineAccountLayout = defineLayoutFor(AccountLayout, [
    'Profile',
    'ResetPassword',
    'API',
], [
    'Account',
]);

export default {
    ...defineDefaultLayout,
    ...defineAuthLayouts,
    ...defineAccountLayout,
};
