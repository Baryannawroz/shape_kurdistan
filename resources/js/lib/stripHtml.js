/**
 * @param {string|null|undefined} html
 * @returns {string}
 */
export function stripHtml(html) {
    if (! html || typeof html !== 'string') {
        return '';
    }

    return html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
}
