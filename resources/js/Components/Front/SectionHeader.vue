<script setup>
import RichContent from '@/Components/Front/RichContent.vue';

defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, required: true },
    lead: { type: String, default: '' },
    leadHtml: { type: String, default: '' },
    centered: { type: Boolean, default: true },
});
</script>

<template>
    <div :class="centered ? 'mx-auto max-w-3xl text-center' : 'max-w-2xl'">
        <p v-if="eyebrow" class="clover-eyebrow">{{ eyebrow }}</p>
        <h2 class="clover-section-title" :class="eyebrow ? 'mt-3' : ''">{{ title }}</h2>
        <RichContent
            v-if="leadHtml"
            :html="leadHtml"
            :prose-class="(centered ? 'mx-auto ' : '') + 'prose prose-lg clover-section-lead max-w-none text-clover-muted prose-strong:text-clover-ink'"
        />
        <p v-else-if="lead" class="clover-section-lead">{{ lead }}</p>
    </div>
</template>
