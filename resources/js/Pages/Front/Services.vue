<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon } from '@heroicons/vue/24/outline';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import CloverIcon from '@/Components/Front/CloverIcon.vue';
import { r } from '@/lib/route.js';
import { stripHtml } from '@/lib/stripHtml.js';

defineProps({ services: Array });

const page = usePage();
const locale = page.props.locale;
</script>

<template>
    <AppLayout title="Services">
        <Head title="Services" />
        <PageHero
            eyebrow="Capabilities"
            title="Services"
            lead="Explore how we help teams ship — from strategy and UX to engineering, performance, and analytics."
        />
        <section class="mx-auto max-w-6xl px-4 py-16 lg:px-8">
            <div v-if="! services?.length" class="clover-card p-12 text-center text-clover-muted">
                No services are published for this language yet.
            </div>
            <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <article v-for="(s, index) in services" :key="s.id" class="clover-card-hover group flex flex-col p-8">
                    <CloverIcon :index="index" size="md" class="mb-5" />
                    <h2 class="text-xl font-semibold text-clover-ink">{{ s.title }}</h2>
                    <p class="mt-3 flex-1 text-sm leading-relaxed text-clover-muted line-clamp-4">{{ stripHtml(s.description) }}</p>
                    <Link
                        :href="r('site.services.show', { locale, slug: s.slug })"
                        class="mt-6 inline-flex items-center gap-1 text-sm font-semibold text-primary transition group-hover:gap-2"
                    >
                        Learn more
                        <ArrowRightIcon class="h-4 w-4" aria-hidden="true" />
                    </Link>
                </article>
            </div>
        </section>
    </AppLayout>
</template>
