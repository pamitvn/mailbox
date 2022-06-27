import { usePage } from '@inertiajs/inertia-vue3';
import _ from 'lodash';
import { reactiveToJson } from '~/utils';
import Auth from '~/types/Uses/Auth';

/**
 * Get authentication info
 */
function useAuth(): Auth.Type;
function useAuth<T = Auth.Type, K extends Auth.StringType>(path: K): T[K];
function useAuth<T = Auth.Type, K extends Auth.StringType>(path: K, $default): T[K] {
    const pageProps = _.get(reactiveToJson(usePage().props.value), 'auth', {});
    return !path ? pageProps : _.get(pageProps, path, $default);
}

useAuth('isLoggedIn');

export default useAuth;
