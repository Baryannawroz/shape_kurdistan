<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { PhotoIcon } from '@heroicons/vue/24/outline';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import ImageLightbox from '@/Components/Front/ImageLightbox.vue';
import { r } from '@/lib/route.js';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    projects: Array,
    categories: Array,
});

const page = usePage();
const locale = page.props.locale;
const filter = ref('All');
const lightbox = ref({
    open: false,
    src: '',
    alt: '',
});

const filtered = computed(() => {
    if (filter.value === 'All') {
        return props.projects;
    }

    return props.projects.filter((p) => p.category === filter.value);
});

function openLightbox(project, event) {
    if (! project.image) {
        return;
    }
    event.preventDefault();
    event.stopPropagation();
    lightbox.value = {
        open: true,
        src: project.image,
        alt: project.title || '',
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
    <AppLayout title="Portfolio">
        <Head title="Portfolio" />
        <PageHero eyebrow="Selected work" title="Portfolio" lead="Filter by discipline — click an image to preview it full size.">
            <div class="mt-8 flex flex-wrap gap-2">
                <button
                    type="button"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    :class="filter === 'All' ? 'bg-clover-dark text-white' : 'border border-clover-border bg-white text-clover-muted hover:text-clover-ink'"
                    @click="filter = 'All'"
                >
                    All
                </button>
                <button
                    v-for="c in categories"
                    :key="c"
                    type="button"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                    :class="filter === c ? 'bg-clover-dark text-white' : 'border border-clover-border bg-white text-clover-muted hover:text-clover-ink'"
                    @click="filter = c"
                >
                    {{ c }}
                </button>
            </div>
        </PageHero>

        <section class="mx-auto max-w-6xl px-4 py-14 lg:px-8">
            <div class="columns-1 gap-6 space-y-6 md:columns-2 lg:columns-3">
                <article
                    v-for="p in filtered"
                    :key="p.id"
                    class="break-inside-avoid overflow-hidden rounded-clover border border-clover-border bg-white shadow-card-soft transition hover:border-primary/30 hover:shadow-glow"
                >
                    <div
                        class="relative aspect-video cursor-zoom-in bg-clover-bg"
                        role="button"
                        tabindex="0"
                        @click="openLightbox(p, $event)"
                        @keydown.enter="(e) => { if (p.image) { e.preventDefault(); openLightbox(p, e); } }"
                    >
                        <img v-if="p.image" :src="p.image" class="h-full w-full object-cover transition duration-500 hover:scale-[1.02]" alt="" />
                        <div v-else class="flex h-full items-center justify-center">
                            <PhotoIcon class="h-12 w-12 text-clover-muted/40" aria-hidden="true" />
                        </div>
                        <span
                            v-if="p.image"
                            class="pointer-events-none absolute bottom-3 end-3 rounded-full bg-clover-dark/80 px-3 py-1 text-xs font-semibold text-white backdrop-blur-sm"
                        >
                            Zoom
                        </span>
                    </div>
                    <Link :href="r('site.portfolio.show', { locale, slug: p.slug })" class="block p-5 transition hover:bg-clover-bg/50">
                        <p class="text-xs font-semibold uppercase tracking-wider text-primary">{{ p.category }}</p>
                        <p class="mt-1 text-lg font-semibold text-clover-ink">{{ p.title }}</p>
                        <p class="mt-2 inline-flex items-center gap-1 text-sm font-semibold text-primary">
                            Open project
                            <span aria-hidden="true">→</span>
                        </p>
                    </Link>
                </article>
            </div>
        </section>

        <ImageLightbox :open="lightbox.open" :src="lightbox.src" :alt="lightbox.alt" @close="closeLightbox" />
    </AppLayout>
</template>
