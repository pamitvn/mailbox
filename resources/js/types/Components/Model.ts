export namespace Model {
    export interface Bootstrap {
        toggle: () => void;
        show: (relatedTarget: HTMLElement | string) => void;
        hide: () => void;
        handleUpdate: () => void;
        dispose: () => void;
    }
}

export default Model;
