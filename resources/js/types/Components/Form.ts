import { DefineComponent } from 'vue';

export namespace Form {
    export namespace Input {
        export interface Props {
            modelValue: string | number;
            label: string;
            error: string;
            allowChange: string;
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
}

export default Form;
