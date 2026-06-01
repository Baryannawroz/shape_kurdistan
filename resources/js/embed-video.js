import { buildYoutubeEmbedUrl } from '@/lib/youtubeEmbed.js';

function buildHostedPlayerMarkup(src) {
    return `
        <div class="hosted-video-player not-prose my-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-950 shadow-lg ring-1 ring-slate-900/5">
            <button type="button" class="hosted-video-poster group relative flex aspect-video w-full cursor-pointer items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-emerald-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600" aria-label="Play video">
                <span class="relative flex h-20 w-20 items-center justify-center rounded-full bg-white/95 text-emerald-700 shadow-xl ring-4 ring-white/20 transition group-hover:scale-105 group-hover:bg-white">
                    <svg class="ms-1 h-10 w-10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.5 5.5v13l11-6.5-11-6.5Z"/></svg>
                </span>
            </button>
            <div class="hosted-video-frame relative hidden aspect-video w-full bg-black">
                <video class="h-full w-full object-contain" src="" data-video-src="${src}" controls playsinline controlsList="nodownload noplaybackrate" disablePictureInPicture></video>
            </div>
        </div>
    `;
}

function buildYoutubePlayerMarkup(videoId) {
    const iframeSrc = buildYoutubeEmbedUrl(videoId, {
        origin: window.location.origin,
        autoplay: true,
    });

    return `
        <div class="youtube-video-player not-prose my-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-950 shadow-lg ring-1 ring-slate-900/5">
            <button type="button" class="youtube-video-poster group relative flex aspect-video w-full cursor-pointer items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-emerald-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600" aria-label="Play video">
                <span class="relative flex h-20 w-20 items-center justify-center rounded-full bg-white/95 text-emerald-700 shadow-xl ring-4 ring-white/20 transition group-hover:scale-105 group-hover:bg-white">
                    <svg class="ms-1 h-10 w-10" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.5 5.5v13l11-6.5-11-6.5Z"/></svg>
                </span>
            </button>
            <div class="youtube-video-frame relative hidden aspect-video w-full">
                <iframe src="" data-embed-src="${iframeSrc}" class="absolute inset-0 h-full w-full border-0" title="Video" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                <span class="pointer-events-none absolute start-0 top-0 z-10 h-24 w-72 bg-gradient-to-br from-slate-950 via-slate-950/95 to-transparent" aria-hidden="true"></span>
                <span class="pointer-events-none absolute bottom-0 end-0 z-10 h-20 w-52 bg-gradient-to-tl from-slate-950 via-slate-950 to-transparent" aria-hidden="true"></span>
            </div>
        </div>
    `;
}

function wireHostedPlayer(root) {
    const poster = root.querySelector('.hosted-video-poster');
    const frame = root.querySelector('.hosted-video-frame');
    const video = root.querySelector('video[data-video-src]');

    if (!poster || !frame || !video) {
        return;
    }

    poster.addEventListener('click', () => {
        video.src = video.dataset.videoSrc ?? '';
        poster.classList.add('hidden');
        frame.classList.remove('hidden');
        video.play();
    });

    root.addEventListener('contextmenu', (event) => event.preventDefault());
    video.addEventListener('contextmenu', (event) => event.preventDefault());
}

/**
 * @param {ParentNode} [root]
 */
function wireYoutubePlayer(root) {
    const poster = root.querySelector('.youtube-video-poster');
    const frame = root.querySelector('.youtube-video-frame');
    const iframe = root.querySelector('iframe[data-embed-src]');

    if (!poster || !frame || !iframe) {
        return;
    }

    poster.addEventListener('click', () => {
        iframe.src = iframe.dataset.embedSrc ?? '';
        poster.classList.add('hidden');
        frame.classList.remove('hidden');
    });

    root.addEventListener('contextmenu', (event) => event.preventDefault());
}

export function initHostedVideoPlaceholders(root = document) {
    root.querySelectorAll('.hosted-video[data-video-src]:not([data-video-ready])').forEach((placeholder) => {
        const src = placeholder.dataset.videoSrc;

        if (!src) {
            return;
        }

        placeholder.dataset.videoReady = '1';
        placeholder.outerHTML = buildHostedPlayerMarkup(src);
    });

    root.querySelectorAll('.youtube-video[data-youtube-id]:not([data-video-ready])').forEach((placeholder) => {
        const videoId = placeholder.dataset.youtubeId;

        if (!videoId) {
            return;
        }

        placeholder.dataset.videoReady = '1';
        placeholder.outerHTML = buildYoutubePlayerMarkup(videoId);
    });

    root.querySelectorAll('.hosted-video-player:not([data-video-wired])').forEach((player) => {
        player.dataset.videoWired = '1';
        wireHostedPlayer(player);
    });

    root.querySelectorAll('.youtube-video-player:not([data-video-wired])').forEach((player) => {
        player.dataset.videoWired = '1';
        wireYoutubePlayer(player);
    });
}
