import _ from 'lodash';
import qs from 'querystring';
import { reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const usePagination = () => {
    const url = reactive(new URL(location.href));
    const query = reactive(qs.parse(url.searchParams.toString()));

    const search = ref<string>(query.search as string ?? '');
    const perPage = ref<number | string>(query.perPage as number ?? 10);
    const isTableChange = ref(false);

    watch(search, _.debounce(function(val) {
        _.set(query, 'search', val ?? null);
        isTableChange.value = true;
    }, 300));
    watch(perPage, _.debounce(function(val) {
        _.set(query, 'perPage', val ?? null);
        isTableChange.value = true;
    }, 300));
    watch(query, (val) => {
        url.searchParams.forEach((value, name) => url.searchParams.delete(name));

        Object.keys(val).forEach((item) => {
            if (!_.get(query, item)) {
                url.searchParams.delete(item);
                return;
            }

            url.searchParams.set(item, _.get(query, item));
        });

        if (isTableChange.value) {
            url.searchParams.delete('page');
            isTableChange.value = false;
        }

        url.searchParams.sort();

        Inertia.replace(url.toString());
    });

    return { search, perPage };
};

export default usePagination;
