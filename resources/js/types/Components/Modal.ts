export namespace Model {
    export interface Bootstrap {
        toggle: () => void;
        show: (relatedTarget?: HTMLElement | string) => void;
        hide: () => void;
        handleUpdate: () => void;
        dispose: () => void;

        [key: string]: any;
    }
}

export default Model;
