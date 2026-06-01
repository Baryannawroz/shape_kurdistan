<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import ImageLightbox from '@/Components/Front/ImageLightbox.vue';
import RichContent from '@/Components/Front/RichContent.vue';
import { r } from '@/lib/route.js';

defineProps({
    project: Object,
    related: Array,
});

const page = usePage();
const locale = page.props.locale;
const lightbox = ref({
    open: false,
    src: '',
    alt: '',
});

function openGalleryLightbox(src, alt) {
    lightbox.value = {
        open: true,
        src,
        alt: alt || '',
    };
}

function closeLightbox() {
    lightbox.value = {
        open: false,
        src: '',
        alt: '',
    };
}
</script>

<template>
    <AppLayout :title="project.title">
        <Head :title="project.title" />
        <PageHero
            :title="project.title"
            :lead="`${project.client} · ${project.year}`"
            back-label="Portfolio"
            :back-href="r('site.portfolio', { locale })"
        />
        <section class="mx-auto max-w-5xl px-4 py-14 lg:px-8">
            <RichContent
                :html="project.description"
                prose-class="prose prose-lg max-w-none text-clover-muted prose-headings:text-clover-ink prose-a:text-primary"
            />
            <div v-if="project.gallery?.length" class="mt-10 grid gap-4 sm:grid-cols-2">
                <button
                    v-for="(g, i) in project.gallery"
                    :key="i"
                    type="button"
                    class="group block overflow-hidden rounded-clover border border-clover-border bg-clover-bg shadow-sm transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    @click="openGalleryLightbox(g, project.title)"
                >
                    <img :src="g" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]" alt="" />
                </button>
            </div>
            <ImageLightbox :open="lightbox.open" :src="lightbox.src" :alt="lightbox.alt" @close="closeLightbox" />
            <div v-if="related.length" class="mt-16 border-t border-clover-border pt-12">
                <h2 class="text-2xl font-bold text-clover-ink">Related</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <Link
                        v-for="p in related"
                        :key="p.id"
                        :href="r('site.portfolio.show', { locale, slug: p.slug })"
                        class="clover-card-hover flex gap-4 p-4"
                    >
                        <img v-if="p.image" :src="p.image" class="h-20 w-28 shrink-0 rounded-lg object-cover" alt="" />
                        <span class="font-semibold text-clover-ink">{{ p.title }}</span>
                    </Link>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
