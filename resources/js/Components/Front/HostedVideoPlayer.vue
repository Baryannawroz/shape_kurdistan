<script setup>
import { ref } from 'vue';
import { PlayIcon } from '@heroicons/vue/24/solid';

defineProps({
    src: { type: String, required: true },
    title: { type: String, default: 'Video' },
});

const isPlaying = ref(false);

function play() {
    isPlaying.value = true;
}
</script>

<template>
    <div
        class="hosted-video-player not-prose my-8 overflow-hidden rounded-2xl border border-slate-200/80 bg-slate-950 shadow-lg ring-1 ring-slate-900/5"
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

        <div v-else class="relative aspect-video w-full bg-black">
            <video
                class="h-full w-full object-contain"
                :src="src"
                controls
                playsinline
                controlsList="nodownload noplaybackrate"
                disablePictureInPicture
                @contextmenu.prevent
            />
        </div>
    </div>
</template>
