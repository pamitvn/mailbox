import { Utils } from '~/types';

declare module '@vue/runtime-core' {
    export interface ComponentCustomProperties {
        $route: Utils.Common.UseRoute;
    }
}

export {};  // Important! See note.
