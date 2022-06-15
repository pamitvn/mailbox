import * as Lodash from 'lodash';
import { DefineComponent } from 'vue';
import { Utils } from '~/types';

export namespace Table {
    export interface Props<RecordType = object> {
        search: string;
        page: string | number;
        perPage: string | number;
        data: Utils.Pagination.Type<RecordType>;
        columns: Columns;
        hasCheckbox: boolean;
        checkboxByField: string;
    }

    export interface Column<RecordType = object> {
        path: string;
        label: string;
        display?: (row: RecordType, value: any, lodash: typeof Lodash) => any;
    }

    export type Columns<RecordType = object> = Column<RecordType>[]
    export type Type<RecordType = object> = DefineComponent<Props<RecordType>>
}

export default Table;
