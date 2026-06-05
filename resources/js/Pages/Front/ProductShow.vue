<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import SeoHead from '@/Components/Front/SeoHead.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import RichContent from '@/Components/Front/RichContent.vue';
import { r } from '@/lib/route.js';

defineProps({
    product: Object,
    seo: { type: Object, default: () => ({}) },
});

const page = usePage();
const locale = page.props.locale;
</script>

<template>
    <AppLayout :title="seo.title || product.title">
        <SeoHead :seo="seo" />
        <PageHero
            :eyebrow="product.category?.title"
            :title="product.title"
            back-label="Products"
            :back-href="r('site.products', { locale })"
        >
            <div v-if="product.sku || product.price != null" class="mt-5 flex flex-wrap gap-4 text-sm text-clover-muted">
                <span v-if="product.sku">SKU: {{ product.sku }}</span>
                <span v-if="product.price != null" class="rounded-full border border-clover-border bg-white px-3 py-1 font-semibold text-clover-ink">
                    {{ Number(product.price).toFixed(2) }}
                </span>
            </div>
        </PageHero>
        <section class="mx-auto max-w-4xl px-4 py-12 lg:px-8">
            <div v-if="product.image" class="mb-10 overflow-hidden rounded-clover border border-clover-border bg-clover-bg shadow-inner">
                <img :src="product.image" class="max-h-[28rem] w-full object-contain" alt="" />
            </div>
            <RichContent
                :html="product.description"
                prose-class="prose prose-lg max-w-none text-clover-muted prose-headings:text-clover-ink prose-a:text-primary"
            />
            <div class="mt-12 text-center">
                <Link :href="r('site.contact', { locale })" class="clover-btn-primary">
                    Get Started
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
