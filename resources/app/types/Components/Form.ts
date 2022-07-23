import { DefineComponent } from 'vue';
import { InertiaFormProps } from '@inertiajs/inertia-vue3';

export namespace Form {
    export namespace Input {
        export interface Props {
            label: string;
            modelValue?: string | number;
            helper?: string;
            error?: string;

            allowChange?: boolean;
            required?: boolean;

            type: Form.Input.BaseType;
            BSize?: Form.Input.Size;
            BStyle?: Form.Input.Style;

            [attrs: string]: any;
        }

        export type BaseType =
            'button'
            | 'checkbox'
            | 'color'
            | 'date'
            | 'datetime-local'
            | 'email'
            | 'file'
            | 'hidden'
            | 'image'
            | 'month'
            | 'number'
            | 'password'
            | 'radio'
            | 'range'
            | 'reset'
            | 'search'
            | 'submit'
            | 'tel'
            | 'text'
            | 'time'
            | 'url'
            | 'week';
        export type Size = 'small' | 'default' | 'large';
        export type Style = 'none' | 'simple' | 'underline' | 'solid';
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

    export namespace DynamicForm {
        export interface Props {

        }

        export interface Field<Attrs = object> {
            is?: any;
            allowChange?: boolean;
            attrs: Attrs;
        }

        export interface DefaultValues {
            [key: string]: any;
        }

        export interface Expose {
            form: InertiaFormProps<any>;
            values: DefaultValues;
        }


        export type HandleSubmitForm = (form: InertiaFormProps<any>, values: DefaultValues) => any
        export type Fields<Attrs = object> = Record<string, Field<Attrs>>
        export type Type = DefineComponent<Props>
    }
}

export default Form;
