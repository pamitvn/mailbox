import _ from 'lodash';
import { usePage } from '@inertiajs/inertia-vue3';
import { reactiveToJson } from '~/utils';
import Auth from '~/types/Uses/Auth';

/**
 * Get authentication info
 */
function useAuth(): Auth.Type;
function useAuth<T = Auth.Type, K extends keyof Auth.Type>(path: K): T extends Auth.Type ? T[K] : T;
function useAuth<T = Auth.Type, K extends keyof Auth.Type>(path: K, $default): T extends Auth.Type ? T[K] : T {
    const pageProps = _.get(reactiveToJson(usePage().props.value), 'auth', {});
    return !path ? pageProps : _.get(pageProps, path, $default);
}

export default useAuth;
