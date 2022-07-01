import { ref } from 'vue';
import Modal from '~/types/Components/Modal';

function useModal() {
    const model = ref<Modal.Bootstrap | null>(null);

    const getInterface = (modelInterface) => {
        if (!modelInterface) return;

        model.value = modelInterface;
    };
    const onShow = () => model.value?.show();
    const onHide = () => model.value?.hide();
    const onToggle = () => model.value?.toggle();

    return {
        getInterface,
        onShow,
        onHide,
        onToggle,
    };
};

export default useModal;
