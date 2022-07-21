import { createToast } from 'mosha-vue-toastify';
import { Uses } from '~/types';

/**
 * Use custom toast
 */
const useToast: Uses.Toast = (content, options) => {
    return createToast(content, Object.assign({
        position: 'top-right',
        type: 'default',
        transition: 'zoom',
    }, options ?? {}));
};

export default useToast;
