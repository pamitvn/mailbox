import { DefineComponent } from 'vue';

export namespace Form {
    export namespace Input {
        export interface Props {
            modelValue: string | number;
            label: string;
            error: string;
            allowChange: string;
            onEvent: Object;
        }

        export type Type = DefineComponent<Props>
    }

    export namespace Textarea {
        export interface Props {
            modelValue: string | number;
            label: string;
            error: string;
            allowChange: string;
            onEvent: Object;
        }

        export type Type = DefineComponent<Props>
    }

    export namespace Checkbox {
        export interface Props {
            modelValue: string | number;
            label: string;
            error: string;
            allowChange: string;
        }

        export type Type = DefineComponent<Props>
    }

    export namespace Switch {
        export interface Props {
            modelValue: string | number;
            label: string;
            error: string;
            allowChange: string;
        }

        export type Type = DefineComponent<Props>
    }
}

export default Form;
