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
        path: keyof RecordType | string;
        label: string;
        display?: (row: RecordType, value: any, lodash: typeof Lodash) => any;
    }

    export type Columns<RecordType = object> = Column<RecordType>[]
    export type Type<RecordType = object> = DefineComponent<Partial<Props<RecordType>>>

    export namespace FilterCard {
        export interface Props {
            id: string;
            title: string;
            fields: Fields;
        }

        export interface Field {
            name: string;
            is?: string;
            label?: string;
            options?: any;

            [key: string]: any;
        }

        export type Fields = Field[]
    }
}

export default Table;
