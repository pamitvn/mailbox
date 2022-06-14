import _ from 'lodash';

import route from 'ziggy';
import { Ziggy } from './ziggy';
import { Utils } from '~/types';

export function reactiveToJson<T = Object>(payload: any): T {
    return JSON.parse(JSON.stringify(payload));
};

export const resolveURI = (path: string = '') => {
    return path.startsWith('/') ? path.slice(1) : path;
};

export const isURL = (str: string | URL) => {
    try {
        return Boolean(new URL(str));
    } catch (e) {
        return false;
    }
};

export const matchedURL = (haystack: string | URL, needles: string | URL): boolean => {
    if (isURL(haystack)) haystack = new URL(haystack);
    if (isURL(needles)) needles = new URL(needles);

    let haystackValue = haystack, needlesValue = needles;

    if (haystack instanceof URL && needles instanceof URL) {
        if (haystack.host !== needles.host) return false;

        haystackValue = haystack.pathname;
        needlesValue = needles.pathname;
    }

    if (haystack instanceof URL && _.isString(needles)) {
        haystackValue = haystack.pathname;
    }

    if (_.isString(haystack) && needles instanceof URL) {
        needlesValue = needles.pathname;
    }

    return resolveURI(haystackValue as string) === resolveURI(needlesValue as string);
};

export const useRoute: Utils.Common.UseRoute = (name, params, c) => {
    try {
        return route(name, params, c, Ziggy);
    } catch (e) {
        return 'javascript:;';
    }
};
