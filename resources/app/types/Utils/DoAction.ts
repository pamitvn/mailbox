import { AxiosError, AxiosRequestConfig, AxiosResponse } from 'axios';
import { Api } from '~/types/Utils/Api';

type DoAction = <T = Api.Response>(action: string, options?: Partial<AxiosRequestConfig>) => Promise<AxiosResponse<T>>;

export default DoAction;
