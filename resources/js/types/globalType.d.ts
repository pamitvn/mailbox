import { DefineComponent, h as hType } from 'vue';
import { InertiaHead, InertiaLink } from '@inertiajs/inertia-vue3';
import { VueSelectProps } from 'vue-select';
import { Utils, Components } from '~/types';

declare module '@vue/runtime-core' {
    export interface ComponentCustomProperties {
        $route: Utils.Common.UseRoute;
    }

    export interface ComponentCustomOptions {
        layout?: any;
        allowDefaultLayout?: boolean;
    }

    export interface GlobalComponents {
        TheLink: InertiaLink;
        TheHead: InertiaHead;
        TheInputField: Components.Form.Input.Type;
        TheTextareaField: Components.Form.Textarea.Type;
        TheCheckboxField: Components.Form.Checkbox.Type;
        TheSwitchField: Components.Form.Switch.Type;
        TheTable: Components.Table.Type;
        TheSelect: DefineComponent<VueSelectProps>;
        VueGravatar: Components.VueGravatar.Type;
    }
}

export {};  // Important! See note.
