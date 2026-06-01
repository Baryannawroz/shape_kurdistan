import { normalizeYoutubeVideoId } from '@/lib/youtubeEmbed.js';

const VIDEO_PATH_PATTERN = /^videos\/[a-zA-Z0-9._-]+\.(?:mp4|webm|mov)$/i;

const PATTERNS = [
    {
        type: 'hosted',
        regex: /<div\b[^>]*\bclass="[^"]*\bhosted-video\b[^"]*"[^>]*\bdata-video-src="([^"]+)"[^>]*>\s*<\/div>/gi,
        resolve: (match) => ({ type: 'hosted', src: match[1] }),
    },
    {
        type: 'youtube',
        regex: /<div\b[^>]*\bclass="[^"]*\byoutube-video\b[^"]*"[^>]*\bdata-youtube-id="([a-zA-Z0-9_-]{11})"[^>]*>\s*<\/div>/gi,
        resolve: (match) => {
            const id = normalizeYoutubeVideoId(match[1]);

            return id ? { type: 'youtube', videoId: id } : null;
        },
    },
    {
        type: 'hosted',
        regex: /\[\[video:(videos\/[a-zA-Z0-9._-]+\.(?:mp4|webm|mov))\]\]/gi,
        resolve: (match) => {
            const path = match[1];

            if (!VIDEO_PATH_PATTERN.test(path)) {
                return null;
            }

            const origin = typeof window !== 'undefined' ? window.location.origin : '';

            return { type: 'hosted', src: `${origin}/storage/${path}` };
        },
    },
    {
        type: 'youtube',
        regex: /\[\[(?:video|youtube):([a-zA-Z0-9_-]{11})\]\]/gi,
        resolve: (match) => {
            const id = normalizeYoutubeVideoId(match[1]);

            return id ? { type: 'youtube', videoId: id } : null;
        },
    },
];

/**
 * @param {string|null|undefined} html
 */
export function parseRichContent(html) {
    if (!html) {
        return [{ type: 'html', html: '' }];
    }

    /** @type {Array<{ index: number, length: number, block: object }>} */
    const tokens = [];

    for (const pattern of PATTERNS) {
        const regex = new RegExp(pattern.regex.source, pattern.regex.flags);
        let match;

        while ((match = regex.exec(html)) !== null) {
            const block = pattern.resolve(match);

            if (block) {
                tokens.push({ index: match.index, length: match[0].length, block });
            }
        }
    }

    tokens.sort((a, b) => a.index - b.index);

    /** @type {Array<{ type: 'html', html: string } | { type: 'hosted', src: string } | { type: 'youtube', videoId: string }>} */
    const blocks = [];
    let cursor = 0;

    for (const token of tokens) {
        if (token.index < cursor) {
            continue;
        }

        if (token.index > cursor) {
            blocks.push({ type: 'html', html: html.slice(cursor, token.index) });
        }

        blocks.push(token.block);
        cursor = token.index + token.length;
    }

    if (cursor < html.length) {
        blocks.push({ type: 'html', html: html.slice(cursor) });
    }

    if (blocks.length === 0) {
        blocks.push({ type: 'html', html });
    }

    return blocks;
}
