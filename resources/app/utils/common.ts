import _ from 'lodash';

import route from 'ziggy';
import { Ziggy } from './ziggy';
import { Utils } from '~/types/Utils';

export function reactiveToJson<T = Object>(payload: any): T {
    return _.clone(payload);
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

export const defineLayoutFor = (layout, pages: any[], namespaces: string[] = []) => {
    const results = {};

    const toPath = (namespace: string | string[], path: string | string[]): string => {
        if (_.isString(namespace)) namespace = [namespace];
        if (_.isString(path)) path = [path];

        return _.join([...namespace, ...path], '/');
    };

    _.forEach(_.compact(pages), page => {

        if (_.isArray(page)) {
            const findNamespaceIndex = _.findIndex(page, i => _.isArray(i));

            if (findNamespaceIndex === -1) {
                _.set(results, [...namespaces, ...page].join('/'), layout);
                return;
            }

            const newNamespace = [...namespaces, ..._.filter(page, (__, i) => i < findNamespaceIndex)];
            const subItem = _.get(page, findNamespaceIndex, []);

            _.merge(results, defineLayoutFor(layout, subItem, newNamespace));
            return;
        }

        _.set(results, toPath(namespaces, page as string), layout);
    });

    return results;
};

export const numberFormat = (value) => Intl.NumberFormat('vi-VN').format(value);

export const parseToBoolean = (value: any): boolean => {
    try {
        if (_.isBoolean(value)) return value;

        switch (String(value)) {
            case 'true':
            case 'yes':
            case '1':
                return true;

            case 'false':
            case 'no':
            case '0':
            case 'null':
            case 'undefined':
                return false;
            default:
                return JSON.parse(value);
        }
    } catch (e) {
        return false;
    }
};
