import { defineStore } from 'pinia';


export const useThemeStore = defineStore('theme', {
    state: () => {
        return {
            sidebarExpanded: false,
            sidebarOpen: false,
        };
    },
});
