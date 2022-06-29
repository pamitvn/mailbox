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
        TheSelect: DefineComponent<VueSelectProps>;
        TheTable: Components.Table.Type;
        VueGravatar: DefineComponent<Partial<{
            hash: string
            email: string
            size: number
            defaultImg: '404' | 'mp' | 'identicon' | 'monsterid' | 'wavatar' | 'retro' | 'robohash' | 'blank'
            rating: string
            alt: string
            protocol: 'https' | 'http'
            hostname: string
        }>>;
    }
}

export {};  // Important! See note.
