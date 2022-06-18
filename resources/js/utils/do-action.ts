import axios, { AxiosError, AxiosRequestConfig } from 'axios';
import { Api } from '~/types/Utils/Api';
import { useRoute } from '~/utils/common';
import * as _ from 'lodash';

async function DoAction<T = object, >(action: string, options: Partial<AxiosRequestConfig> = {}): Promise<Api.Response<T>> {
    try {
        const route = useRoute('admin.handle-action');
        const params = {
            action,
        };
        const configs = Object.assign(options, {
            method: 'GET',
            url: route,
            params: Object.keys(options.params ?? {}).length ? Object.assign(options.params, params) : params,
        });

        const response = await axios.request(configs);

        return Promise.resolve(response.data as Api.Response<T>);
    } catch (e) {
        const response = {
            success: false,
            message: e.message,
        };

        if (e instanceof AxiosError) {
            response.message = _.get(e, 'response.data.message', e.message);
        }

        return Promise.resolve(response as Api.Response<T>);
    }
}

export default DoAction;
