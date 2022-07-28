import _ from 'lodash';
import { ref, watch } from 'vue';
import type { Utils } from '~/types/Utils';
import CreateUpdateSocket, { Event, Options } from '~/uses/useCreateUpdateSocket';

interface CUSOptions<T = object> {
    channel: string,
    event: Event,
    options?: Options<T>
}

function usePaginationCUSocket<T = any>(
    paginationData: Utils.Pagination.Type<T>,
    CUOptions: CUSOptions<T>,
) {
    const pagination = ref<Utils.Pagination.Type<T> | any>({});

    const { records, setRecords } = CreateUpdateSocket<T>(CUOptions.channel, CUOptions.event, CUOptions.options);

    const setPagination = (value) => {
        pagination.value = value;
    };
    const update = (val) => {
        const item = _.cloneDeep(val);
        _.forEach(_.keys(item), (key) => {
            pagination.value.data[key] = _.get(item, key as T);
        });
    };

    watch(pagination, () => setRecords(pagination.value?.data ?? []));
    watch(records, update);

    setPagination(paginationData);

    return { paginationData: pagination, setPaginationData: setPagination };
}

export default usePaginationCUSocket;
