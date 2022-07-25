import { ref, watch } from 'vue';

interface Options {
    open?: boolean;
    onShow?: Function;
    onClose?: Function;
}

const useModal = (options: Options = {}) => {
    const open = ref(options?.open || false);

    const close = () => open.value = false;
    const show = () => open.value = true;
    const toggle = () => open.value = !open.value;

    watch(open, (val) => {
        if (val === open.value) return;

        val
            ? options?.onShow()
            : options?.onClose();
    });

    return {
        open,
        close,
        show,
        toggle,
    };
};

export default useModal;
