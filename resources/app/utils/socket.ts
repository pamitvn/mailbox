import { useSelfPage } from '~/uses';
import _, { parseInt } from 'lodash';
import { ResolveType } from '~/uses/useCreateUpdateSocket';

export const resolveCreateByPerPage: ResolveType<object> = (records, event, per_page = null) => {
    const { query } = useSelfPage();
    const currentPage = parseInt(query.value?.cursor);
    const perPage = per_page === null ? parseInt(query.value?.perPage) || 15 : per_page;

    if (currentPage) return records;

    records = [event, ...records];

    return _.slice(records, 0, perPage - 1);
};
