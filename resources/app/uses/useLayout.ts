import { usePage } from '@inertiajs/inertia-vue3';
import _ from 'lodash';
import { reactiveToJson } from '~/utils';

const useLayout = (key = null, $default = null) => {
    const layouts = _.get(reactiveToJson(usePage().props.value), 'layouts', {});
    return !key ? layouts : _.get(layouts, key, $default);
};

export default useLayout;
