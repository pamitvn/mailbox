import * as common from './common';
import * as pagination from './Pagination';
import * as api from './Api';

export namespace Utils {
    export import Common = common;
    export import Pagination = pagination;
    export import Api = api;
    export { default as DoAction } from './DoAction';
}
