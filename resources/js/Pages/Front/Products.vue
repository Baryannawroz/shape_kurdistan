<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ShoppingBagIcon } from '@heroicons/vue/24/outline';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import { stripHtml } from '@/lib/stripHtml.js';
import { r } from '@/lib/route.js';

defineProps({
    categories: Array,
    products: Array,
    activeCategorySlug: { type: String, default: null },
});

const page = usePage();
const locale = page.props.locale;
</script>

<template>
    <AppLayout title="Products">
        <Head title="Products" />
        <PageHero eyebrow="Catalog" title="Products" lead="Browse by category or explore the full catalog.">
            <div class="mt-8 flex flex-wrap gap-2">
                <Link
                    :href="r('site.products', { locale })"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition"
                    :class="! activeCategorySlug ? 'bg-clover-dark text-white' : 'border border-clover-border bg-white text-clover-muted hover:text-clover-ink'"
                >
                    All
                </Link>
                <Link
                    v-for="c in categories"
                    :key="c.id"
                    :href="r('site.products', { locale, category: c.slug })"
                    class="rounded-full px-4 py-2 text-sm font-semibold transition"
                    :class="activeCategorySlug === c.slug ? 'bg-clover-dark text-white' : 'border border-clover-border bg-white text-clover-muted hover:text-clover-ink'"
                >
                    {{ c.title }}
                </Link>
            </div>
        </PageHero>
        <section class="mx-auto max-w-6xl px-4 py-16 lg:px-8">
            <div v-if="! products.length" class="clover-card p-12 text-center text-clover-muted">
                No products in this category yet.
            </div>
            <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="p in products"
                    :key="p.id"
                    :href="r('site.products.show', { locale, slug: p.slug })"
                    class="clover-card-hover group overflow-hidden"
                >
                    <div class="aspect-[4/3] bg-clover-bg">
                        <img v-if="p.image" :src="p.image" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" alt="" />
                        <div v-else class="flex h-full items-center justify-center">
                            <ShoppingBagIcon class="h-12 w-12 text-clover-muted/40" aria-hidden="true" />
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-bold uppercase tracking-wider text-primary">{{ p.category_title }}</p>
                        <p class="mt-1 text-lg font-semibold text-clover-ink">{{ p.title }}</p>
                        <p v-if="p.price != null" class="mt-2 text-sm font-semibold text-clover-ink">{{ Number(p.price).toFixed(2) }}</p>
                        <p class="mt-2 line-clamp-2 text-sm text-clover-muted">{{ stripHtml(p.description) }}</p>
                    </div>
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
