<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';

defineProps({
    eyebrow: { type: String, default: '' },
    title: { type: String, required: true },
    lead: { type: String, default: '' },
    backHref: { type: String, default: '' },
    backLabel: { type: String, default: '' },
});
</script>

<template>
    <section class="clover-page-hero">
        <div class="pointer-events-none absolute inset-0 bg-mesh-warm opacity-70" aria-hidden="true" />
        <div class="relative mx-auto max-w-4xl px-4 lg:px-8">
            <Link
                v-if="backHref"
                :href="backHref"
                class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-clover-muted transition hover:text-clover-ink"
            >
                <ArrowLeftIcon class="h-4 w-4" aria-hidden="true" />
                {{ backLabel }}
            </Link>
            <p v-if="eyebrow" class="clover-eyebrow">{{ eyebrow }}</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight text-clover-ink md:text-5xl">{{ title }}</h1>
            <p v-if="lead" class="mt-4 max-w-2xl text-lg leading-relaxed text-clover-muted">{{ lead }}</p>
            <slot />
        </div>
    </section>
</template>
