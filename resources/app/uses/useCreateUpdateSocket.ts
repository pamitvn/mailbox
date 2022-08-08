import _ from 'lodash';
import { onMounted, onUnmounted, ref } from 'vue';
import { Echo } from '~/utils';

export interface Event {
    create?: string;
    update?: string;
}

export interface Options<T = object> {
    privateChannel?: boolean;
    transFormCreate?: TransFormType<T>;
    transFormUpdate?: TransFormType<T>;
    resolveCreate?: ResolveType<T>;
    resolveUpdate?: ResolveType<T>;
}

export type TransFormType<T> = (list: T[], data: T, index: string | number) => T
export type ResolveType<T> = (list: T[], data: T, ...rest: any) => T[]

function useCreateUpdateSocket<T = object>(
    channel: string,
    event: Event,
    options?: Options<T>,
) {
    const privateChannel = _.get(options, 'privateChannel', true);
    const records = ref<T[] | any>([]);

    const setRecords = (values) => {
        records.value = values;
    };
    const handleCreateEvent = (event) => {

        if (options?.resolveCreate && _.isFunction(options?.resolveCreate)) {
            records.value = options?.resolveCreate(records.value, event);
            return;
        }

        const currentQuery = new URL(location.href);
        const currentPage = Number((currentQuery.searchParams.get('page') || 1));
        const index = _.findIndex(records.value, i => i.id === event.id);

        if (currentPage !== 1 || index !== -1) return;

        const object = options?.transFormCreate
            ? options.transFormCreate(records.value, event, index)
            : event;

        const newArray = [object];
        newArray.push(...(records.value ?? []));

        records.value = newArray;
    };
    const handleUpdateEvent = (event) => {
        if (options?.resolveUpdate && _.isFunction(options?.resolveUpdate)) {
            records.value = options?.resolveUpdate(records.value, event);
            return;
        }

        const index = _.findIndex(records.value, i => i.id === event.id);

        if (!event.id || index === -1) return;

        _.set(records.value, index, options?.transFormUpdate ? options.transFormUpdate(records.value, event, index) : event);
    };

    onMounted(() => {
        const echoChannel = Echo[privateChannel ? 'private' : 'channel'](channel);

        if (event.create) echoChannel.listen(event.create, handleCreateEvent);
        if (event.update) echoChannel.listen(event.update, handleUpdateEvent);

        onUnmounted(() => {
            event.create && echoChannel.stopListening(event.create, handleCreateEvent);
            event.update && echoChannel.stopListening(event.update, handleUpdateEvent);
        });
    });

    return { records, setRecords };
}

export default useCreateUpdateSocket;
