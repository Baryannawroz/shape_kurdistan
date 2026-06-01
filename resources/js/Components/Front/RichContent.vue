<script setup>
import { computed } from 'vue';
import HostedVideoPlayer from '@/Components/Front/HostedVideoPlayer.vue';
import YoutubeVideoPlayer from '@/Components/Front/YoutubeVideoPlayer.vue';
import { parseRichContent } from '@/lib/videoContent.js';

const props = defineProps({
    html: { type: String, default: '' },
    proseClass: {
        type: String,
        default: 'prose prose-lg prose-slate max-w-none prose-headings:font-semibold',
    },
});

const blocks = computed(() => parseRichContent(props.html));
</script>

<template>
    <div :class="proseClass">
        <template v-for="(block, index) in blocks" :key="index">
            <div v-if="block.type === 'html'" v-html="block.html" />
            <HostedVideoPlayer v-else-if="block.type === 'hosted'" :src="block.src" />
            <YoutubeVideoPlayer v-else-if="block.type === 'youtube'" :video-id="block.videoId" />
        </template>
    </div>
</template>
