import _ from 'lodash';
import { onMounted, onUnmounted, reactive, Ref, ref, UnwrapRef, watch, watchEffect } from 'vue';
import { Echo } from '~/utils';

export interface Event {
    create: string;
    update: string;
}

export interface Options<T> {
    transFormCreate: TransFormType<T>;
    transFormUpdate: TransFormType<T>;
}

type TransFormType<T> = (list: T[], data: T, index: string | number) => T

function useCreateUpdateSocket<T = object>(
    channel: string,
    event: Event,
    options?: Options<T>,
) {
    const records = ref<T[] | any>([]);

    const setRecords = (values) => {
        records.value = values;
    };
    const handleCreateEvent = (event) => {
        const currentQuery = new URL(location.href);
        const currentPage = Number((currentQuery.searchParams.get('page') || 1));
        const index = _.findIndex(records.value, i => i.id === event.id);

        if (currentPage !== 1 || index !== -1) return;

        const newArray = [options?.transFormCreate ? options.transFormCreate(records.value, event, index) : event];
        newArray.push(...records);

        records.value = newArray;
    };
    const handleUpdateEvent = (event) => {
        const index = _.findIndex(records.value, i => i.id === event.id);

        if (!event.id || index === -1) return;

        _.set(records.value, index, options?.transFormUpdate ? options.transFormUpdate(records.value, event, index) : event);
    };

    onMounted(() => {
        const echoChannel = Echo.private(channel);

        echoChannel.listen(event.create, handleCreateEvent);
        echoChannel.listen(event.update, handleUpdateEvent);

        onUnmounted(() => {
            echoChannel.stopListening(event.create, handleCreateEvent);
            echoChannel.stopListening(event.update, handleUpdateEvent);
        });
    });

    return { records, setRecords };
}

export default useCreateUpdateSocket;
