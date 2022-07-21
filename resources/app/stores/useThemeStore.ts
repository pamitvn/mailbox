import { defineStore } from 'pinia';


export const useThemeStore = defineStore('theme', {
    state: () => {
        const storedSidebarExpanded = localStorage.getItem('sidebar-expanded');

        return {
            sidebarExpanded: storedSidebarExpanded === null ? false : storedSidebarExpanded === 'true',
            sidebarOpen: false,
        };
    },
});
