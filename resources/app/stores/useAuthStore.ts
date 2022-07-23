import { defineStore } from 'pinia';
import { Models } from '~/types/Models';

interface StateType {
    isLoggedIn: boolean;
    user: Partial<Models.User>;
}

const useAuthStore = defineStore('auth', {
    state: (): StateType => {
        return {
            isLoggedIn: false,
            user: {},
        };
    },
});

export default useAuthStore;
