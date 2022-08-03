import { defineLayoutFor } from '~/utils';

import DefaultLayout from '~/layouts/DefaultLayout.vue';
import AuthLayout from '~/layouts/AuthLayout.vue';
import AccountLayout from '~/layouts/AccountLayout.vue';
import SettingLayout from '~/layouts/SettingLayout.vue';

const defineDefaultLayout = defineLayoutFor(DefaultLayout, [
    'Statistics',
    [
        'Admin',
        [
            'Statistics',
        ],
    ],
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

const defineSettingLayout = defineLayoutFor(SettingLayout, [
    'Manager',
], [
    'Admin/Settings',
]);

export default {
    ...defineDefaultLayout,
    ...defineAuthLayouts,
    ...defineAccountLayout,
    ...defineSettingLayout,
};
