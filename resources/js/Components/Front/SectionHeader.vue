<script setup>
defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, required: true },
    lead: { type: String, default: '' },
    centered: { type: Boolean, default: true },
});
</script>

<template>
    <div :class="centered ? 'mx-auto max-w-3xl text-center' : 'max-w-2xl'">
        <p v-if="eyebrow" class="clover-eyebrow">{{ eyebrow }}</p>
        <h2 class="clover-section-title" :class="eyebrow ? 'mt-3' : ''">{{ title }}</h2>
        <p v-if="lead" class="clover-section-lead">{{ lead }}</p>
    </div>
</template>
