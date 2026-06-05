<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
    seo: {
        type: Object,
        default: () => ({ title: '', description: '', keywords: '' }),
    },
});
</script>

<template>
    <Head :title="seo.title || undefined">
        <meta v-if="seo.description" head-key="description" name="description" :content="seo.description" />
        <meta v-if="seo.keywords" head-key="keywords" name="keywords" :content="seo.keywords" />
        <meta v-if="seo.description" head-key="og-description" property="og:description" :content="seo.description" />
        <meta v-if="seo.title" head-key="og-title" property="og:title" :content="seo.title" />
    </Head>
</template>
