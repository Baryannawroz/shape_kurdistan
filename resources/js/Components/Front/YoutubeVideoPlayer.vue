<script setup>
import { computed, ref } from 'vue';
import { PlayIcon } from '@heroicons/vue/24/solid';
import { buildYoutubeEmbedUrl, normalizeYoutubeVideoId } from '@/lib/youtubeEmbed.js';

const props = defineProps({
    videoId: { type: String, required: true },
    title: { type: String, default: 'Video' },
});

const isPlaying = ref(false);

const normalizedId = computed(() => normalizeYoutubeVideoId(props.videoId));

const iframeSrc = computed(() => {
    if (!isPlaying.value || !normalizedId.value) {
        return '';
    }

    const origin = typeof window !== 'undefined' ? window.location.origin : undefined;

    return buildYoutubeEmbedUrl(normalizedId.value, { origin, autoplay: true });
});

function play() {
    if (normalizedId.value) {
        isPlaying.value = true;
    }
}
</script>

<template>
    <div
        v-if="normalizedId"
        class="youtube-video-player not-prose my-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-950 shadow-lg ring-1 ring-slate-900/5"
        @contextmenu.prevent
    >
        <button
            v-if="!isPlaying"
            type="button"
            class="group relative flex aspect-video w-full cursor-pointer items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-slate-950 to-emerald-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 focus-visible:ring-offset-slate-950"
            :aria-label="`Play ${title}`"
            @click="play"
        >
            <div
                class="pointer-events-none absolute inset-0 opacity-60"
                aria-hidden="true"
                style="
                    background-image: radial-gradient(circle at 30% 20%, rgba(45, 212, 191, 0.25), transparent 45%),
                        radial-gradient(circle at 80% 80%, rgba(15, 118, 110, 0.35), transparent 40%);
                "
            />
            <span
                class="relative flex h-20 w-20 items-center justify-center rounded-full bg-white/95 text-primary shadow-xl ring-4 ring-white/20 transition group-hover:scale-105 group-hover:bg-white"
            >
                <PlayIcon class="ms-1 h-10 w-10" aria-hidden="true" />
            </span>
        </button>

        <div v-else class="relative aspect-video w-full">
            <iframe
                :src="iframeSrc"
                class="absolute inset-0 h-full w-full border-0"
                :title="title"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
            />
            <span
                class="pointer-events-none absolute start-0 top-0 z-10 h-24 w-72 bg-gradient-to-br from-slate-950 via-slate-950/95 to-transparent"
                aria-hidden="true"
            />
            <span
                class="pointer-events-none absolute bottom-0 end-0 z-10 h-20 w-52 bg-gradient-to-tl from-slate-950 via-slate-950 to-transparent"
                aria-hidden="true"
            />
            <span
                class="pointer-events-none absolute bottom-0 start-0 z-10 h-14 w-40 bg-gradient-to-tr from-slate-950 via-slate-950/90 to-transparent"
                aria-hidden="true"
            />
        </div>
    </div>
</template>
