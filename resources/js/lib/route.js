import { route } from 'ziggy-js';
import { usePage } from '@inertiajs/vue3';

/**
 * @param {string} name
 * @param {Record<string, unknown>} [params]
 * @param {boolean} [absolute]
 */
export function r(name, params = {}, absolute = true) {
    const ziggy = usePage().props.ziggy;

    return route(name, params, absolute, ziggy);
}
