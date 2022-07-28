import { useSelfPage } from '~/uses';
import _, { parseInt } from 'lodash';
import { ResolveType } from '~/uses/useCreateUpdateSocket';

export const resolveCreateByPerPage: ResolveType<object> = (records, event) => {
    const { query } = useSelfPage();
    const currentPage = parseInt(query.value?.cursor);
    const perPage = parseInt(query.value?.perPage) || 15;

    if (currentPage) return records;

    records = [event, ...records];

    return _.slice(records, 0, perPage - 1);
};
