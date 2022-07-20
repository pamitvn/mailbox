import _ from 'lodash';
import { onMounted, onUnmounted, ref } from 'vue';
import { Echo } from '~/utils';

export interface Event {
    create?: string;
    update?: string;
}

export interface Options<T> {
    privateChannel?: boolean;
    transFormCreate?: TransFormType<T>;
    transFormUpdate?: TransFormType<T>;
}

type TransFormType<T> = (list: T[], data: T, index: string | number) => T

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
        const index = _.findIndex(records.value, i => i.id === event.id);

        if (!event.id || index === -1) return;

        _.set(records.value, index, options?.transFormUpdate ? options.transFormUpdate(records.value, event, index) : event);
    };

    onMounted(() => {
        const echoChannel = Echo[privateChannel ? 'private' : 'channel'](channel);

        event.create && echoChannel.listen(event.create, handleCreateEvent);
        event.update && echoChannel.listen(event.update, handleUpdateEvent);

        onUnmounted(() => {
            event.create && echoChannel.stopListening(event.create, handleCreateEvent);
            event.update && echoChannel.stopListening(event.update, handleUpdateEvent);
        });
    });

    return { records, setRecords };
}

export default useCreateUpdateSocket;
