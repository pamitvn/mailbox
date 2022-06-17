import { DefineComponent, h as hType } from 'vue';
import { Utils } from '~/types';
import { InertiaHead, InertiaLink } from '@inertiajs/inertia-vue3';
import Components from '~/types/Components';
import { VueSelectProps } from 'vue-select';

type layoutFn = (h: hType, page) => any

declare module '@vue/runtime-core' {
    export interface ComponentCustomProperties {
        $route: Utils.Common.UseRoute;
    }

    export interface ComponentCustomOptions {
        layout?: DefineComponent | layoutFn;
    }

    export interface GlobalComponents {
        TheLink: InertiaLink;
        TheHead: InertiaHead;
        TheInputField: Components.Form.Input.Type;
        TheCheckboxField: Components.Form.Checkbox.Type;
        TheSelect: DefineComponent<VueSelectProps>;
        TheTable: Components.Table.Type;
    }
}

export {};  // Important! See note.
