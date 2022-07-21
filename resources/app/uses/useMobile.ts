import { ref, watchEffect } from 'vue';
import { useWindowSize } from '@vueuse/core';

const useMobile = () => {
    const isMobile = ref(false);

    const { width } = useWindowSize();

    watchEffect(() => {
        const maxMobileWidth = 575;

        isMobile.value = width.value <= maxMobileWidth;
    });

    return {
        isMobile,
    };
};

export default useMobile;
