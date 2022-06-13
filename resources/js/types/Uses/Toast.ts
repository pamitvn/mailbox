import { ToastContent, ToastOptions } from 'mosha-vue-toastify';

type Toast = (content: ToastContent, options?: ToastOptions) => { close: () => void }

export default Toast;
