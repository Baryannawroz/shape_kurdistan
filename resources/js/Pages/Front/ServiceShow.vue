<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import RichContent from '@/Components/Front/RichContent.vue';
import { r } from '@/lib/route.js';
import { usePage } from '@inertiajs/vue3';

defineProps({ service: Object });

const page = usePage();
const locale = page.props.locale;
</script>

<template>
    <AppLayout :title="service.title">
        <Head :title="service.title" />
        <PageHero
            :title="service.title"
            back-label="Services"
            :back-href="r('site.services', { locale })"
        />
        <section class="mx-auto max-w-4xl px-4 py-14 lg:px-8">
            <RichContent
                :html="service.description"
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
