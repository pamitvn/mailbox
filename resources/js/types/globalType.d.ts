import { UseRoute } from '~/types/Utils';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $route: UseRoute;
    }
}

export {};  // Important! See note.
