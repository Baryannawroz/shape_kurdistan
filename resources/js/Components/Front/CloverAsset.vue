<script setup>
defineProps({
    /** File name inside public/images/clover/ e.g. "hero-widget.svg" */
    src: { type: String, required: true },
    alt: { type: String, default: '' },
    aspect: { type: String, default: 'aspect-[16/10]' },
    contain: { type: Boolean, default: false },
});

const basePath = '/images/clover/';
</script>

<template>
    <img
        :src="`${basePath}${src}`"
        :alt="alt"
        :class="[
            aspect,
            contain ? 'object-contain p-6' : 'object-cover',
            'h-full w-full',
        ]"
        loading="lazy"
        @error="($event.target).style.display = 'none'"
    />
</template>
