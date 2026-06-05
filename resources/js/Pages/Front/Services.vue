<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowRightIcon } from '@heroicons/vue/24/outline';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import PageInlineEdit from '@/Components/Admin/PageInlineEdit.vue';
import PageSeoInlineEdit from '@/Components/Admin/PageSeoInlineEdit.vue';
import SeoHead from '@/Components/Front/SeoHead.vue';
import CloverIcon from '@/Components/Front/CloverIcon.vue';
import { r } from '@/lib/route.js';
import { stripHtml } from '@/lib/stripHtml.js';

defineProps({
    pageContent: { type: Object, default: null },
    localeMeta: { type: Array, default: () => [] },
    intro: { type: Object, default: () => ({}) },
    services: Array,
    seo: { type: Object, default: () => ({}) },
    seoSettings: { type: Object, default: null },
});

const page = usePage();
const locale = page.props.locale;
</script>

<template>
    <AppLayout :title="seo.title || 'Services'">
        <SeoHead :seo="seo" />
        <PageHero
            :eyebrow="intro.eyebrow"
            :title="intro.title"
            :lead="intro.lead"
            :lead-html="intro.leadHtml"
        />
        <section class="mx-auto max-w-6xl px-4 py-16 lg:px-8">
            <PageSeoInlineEdit
                v-if="seoSettings"
                page-key="services"
                :seo-settings="seoSettings"
                :locale-meta="localeMeta"
            />
            <PageInlineEdit
                v-if="pageContent"
                :page="pageContent"
                :locale-meta="localeMeta"
                label="Edit services page"
                content-variant="basic"
            />
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
