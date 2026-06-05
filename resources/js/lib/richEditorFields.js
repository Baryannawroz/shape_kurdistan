/**
 * Setting keys that should use the rich text editor in CMS forms.
 *
 * @param {string} key
 */
export function settingUsesRichEditor(key) {
    return /(subheadline|excerpt|description|content|address|headline)_(en|ckb|ar)$/.test(key)
        || key.startsWith('general.about_excerpt_')
        || /^home\.[a-z_]+_(lead|body)_(en|ckb|ar)$/.test(key);
}
