const VIDEO_ID_PATTERN = /^[a-zA-Z0-9_-]{11}$/;

/**
 * @param {string|null|undefined} input
 * @returns {string|null}
 */
export function normalizeYoutubeVideoId(input) {
    if (!input || typeof input !== 'string') {
        return null;
    }

    const trimmed = input.trim();

    if (VIDEO_ID_PATTERN.test(trimmed)) {
        return trimmed;
    }

    const match = trimmed.match(
        /(?:youtube\.com\/watch\?[^#]*&?v=|youtube\.com\/watch\?v=|youtu\.be\/|youtube-nocookie\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
    );

    return match?.[1] && VIDEO_ID_PATTERN.test(match[1]) ? match[1] : null;
}

/**
 * @param {string} videoId
 * @param {{ origin?: string, autoplay?: boolean }} [options]
 * @returns {string}
 */
export function buildYoutubeEmbedUrl(videoId, options = {}) {
    const id = normalizeYoutubeVideoId(videoId);

    if (!id) {
        return '';
    }

    const params = new URLSearchParams({
        modestbranding: '1',
        rel: '0',
        iv_load_policy: '3',
        disablekb: '1',
        playsinline: '1',
        controls: '1',
        fs: '1',
    });

    if (options.autoplay) {
        params.set('autoplay', '1');
    }

    if (options.origin) {
        params.set('origin', options.origin);
    }

    return `https://www.youtube-nocookie.com/embed/${id}?${params.toString()}`;
}
