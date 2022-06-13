import { usePage } from '@inertiajs/inertia-vue3';
import _ from 'lodash';
import { Models } from '~/types';
import { reactiveToJson } from '~/utils';

/**
 * Get authentication info
 */
function useAuth(): Models.User;
function useAuth<T>(path: string | string[]): T;
function useAuth<T>(path: string | string[], $default): T {
    const pageProps = _.get(reactiveToJson(usePage().props.value), 'auth', {});
    return !path ? pageProps : _.get(pageProps, path, $default);
}

export default useAuth;
