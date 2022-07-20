import _ from 'lodash';
import resolveConfig from 'tailwindcss/resolveConfig';

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

export const randomString = (length: number = 5): string => {
    return new Array(length).join().replace(/(.|$)/g, function() {
        return ((Math.random() * 36) | 0).toString(36)[Math.random() < .5 ? 'toString' : 'toUpperCase']();
    });
};

export const tailwindConfig = () => {
    // Tailwind config
    return resolveConfig('./tailwind.config.js');
};

export const hexToRGB = (h: string) => {
    let r: string = '0';
    let g: string = '0';
    let b: string = '0';

    if (h.length === 4) {
        r = `0x${h[1]}${h[1]}`;
        g = `0x${h[2]}${h[2]}`;
        b = `0x${h[3]}${h[3]}`;
    } else if (h.length === 7) {
        r = `0x${h[1]}${h[2]}`;
        g = `0x${h[3]}${h[4]}`;
        b = `0x${h[5]}${h[6]}`;
    }

    return `${+r},${+g},${+b}`;
};

export const formatValue = (value) => Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    maximumSignificantDigits: 3,
}).format(value);

export const formatThousands = (value) => Intl.NumberFormat('en-US', {
    maximumSignificantDigits: 3,
}).format(value);
