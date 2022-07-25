import { ref, watch } from 'vue';
import _ from 'lodash';

interface Options {
    open?: boolean;
    onShow?: Function;
    onClose?: Function;
}

const useModal = (options?: Options) => {
    const open = ref(options?.open || false);

    const close = () => open.value = false;
    const show = () => open.value = true;
    const toggle = () => open.value = !open.value;

    watch(open, (val) => {
        val
            ? _.isFunction(options?.onShow) && options?.onShow()
            : _.isFunction(options?.onClose) && options?.onClose();
    });

    return {
        open,
        close,
        show,
        toggle,
    };
};

export default useModal;
