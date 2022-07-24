import _ from 'lodash';
import qs from 'query-string';
import { reactive, ref, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import type Table from '~/types/Components/Table';

const usePagination = <DataType = object>(columns?: Table.Columns<DataType>) => {
    const perPageSelectOptions = reactive({
        5: '5 items',
        10: '10 items',
        15: '15 items',
        20: '20 items',
        25: '25 items',
        50: '50 items',
        100: '100 items',
    });
    const url = reactive(new URL(location.href));
    const query = reactive(qs.parse(url.searchParams.toString()));

    const search = ref<string>(query.search as string ?? '');
    const perPage = ref<number | string>(query.perPage as number ?? 10);
    const isTableChange = ref(false);

    if (!columns) {
        columns = [];
    }

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

        url.searchParams.sort();

        Inertia.replace(url.toString());
    });

    return { search, perPage, perPageSelectOptions, columns };
};

export default usePagination;
