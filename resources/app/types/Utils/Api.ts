export namespace Api {
    export interface ErrorRecords {
        [path: string]: string[];
    }

    export interface Response<T = object> {
        success: boolean;
        message?: string;
        errors?: ErrorRecords;
        data?: T;
    }
}
