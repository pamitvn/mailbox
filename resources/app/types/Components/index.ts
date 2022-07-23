import { DefineComponent } from 'vue';

import form from './Form';
import table from './Table';

export namespace Components {
    export import Form = form;
    export import Table = table;
    export import Modal = modal;
    export namespace VueGravatar {
        export interface Props {
            hash: string;
            email: string;
            size: number;
            defaultImg: '404' | 'mp' | 'identicon' | 'monsterid' | 'wavatar' | 'retro' | 'robohash' | 'blank';
            rating: string;
            alt: string;
            protocol: 'https' | 'http';
            hostname: string;
        }

        export type Type = DefineComponent<Partial<Props>>
    }
}

export default Components;
