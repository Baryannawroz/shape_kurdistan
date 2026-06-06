import { usePage } from '@inertiajs/vue3';

/**
 * @param {unknown} params
 * @returns {Record<string, unknown>}
 */
function normalizeParams(params) {
    if (params === null || params === undefined) {
        return {};
    }

    if (typeof params === 'object' && ! Array.isArray(params)) {
        return params;
    }

    return { id: params };
}

/**
 * @param {string} uri
 * @param {Record<string, unknown>} params
 */
function applyParams(uri, params) {
    let url = uri;

    for (const [key, value] of Object.entries(params)) {
        const encoded = encodeURIComponent(String(value));

        url = url.replace(`{${key}}`, encoded);
        url = url.replace(`{${key}?}`, encoded);
    }

    if (/\{[^}]+\}/.test(url)) {
        throw new Error(`Missing route parameters for ${url}`);
    }

    if (url.startsWith('http')) {
        return url;
    }

    return `${window.location.origin}${url.startsWith('/') ? url : `/${url}`}`;
}

/**
 * @param {string} name
 * @param {Record<string, unknown>|number|string|null} [params]
 * @param {boolean} [absolute]
 */
export function r(name, params = {}, absolute = true) {
    const patterns = usePage().props.adminRoutes ?? {};
    const uri = patterns[name];

    if (! uri) {
        throw new Error(`Unknown route: ${name}`);
    }

    const url = applyParams(uri, normalizeParams(params));

    if (! absolute) {
        return url.replace(window.location.origin, '');
    }

    return url;
}
