import _ from 'lodash';
import qs from 'query-string';
import { computed, ref, watch } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';

const useSelfPage = () => {
    const query = ref({});

    const url = computed(() => usePage().url.value);

    watch(() => url.value, (val) => {
        const queryString = _.last(_.split(val, '?'));

        query.value = qs.parse(queryString);
    });

    return { query };
};

export default useSelfPage;
