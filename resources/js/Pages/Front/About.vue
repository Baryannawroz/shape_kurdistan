<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import RichContent from '@/Components/Front/RichContent.vue';

defineProps({
    title: String,
    content: String,
    team: Array,
});
</script>

<template>
    <AppLayout :title="title">
        <Head :title="title" />
        <PageHero
            eyebrow="About"
            :title="title"
            lead="Our story, craft, and how we partner with teams like yours."
        />
        <section class="mx-auto max-w-4xl px-4 py-14 lg:px-8">
            <RichContent
                :html="content"
                prose-class="prose prose-lg max-w-none text-clover-muted prose-headings:text-clover-ink prose-a:text-primary"
            />
        </section>
        <section v-if="team && team.length" class="border-t border-clover-border bg-white px-4 py-20 lg:px-8">
            <div class="mx-auto max-w-6xl">
                <h2 class="clover-section-title text-center">Team</h2>
                <div class="mt-12 grid gap-6 sm:grid-cols-2 md:grid-cols-3">
                    <div v-for="m in team" :key="m.id" class="clover-card-hover p-8 text-center">
                        <div class="mx-auto h-28 w-28 overflow-hidden rounded-full bg-clover-bg ring-2 ring-white">
                            <img v-if="m.photo" :src="m.photo" class="h-full w-full object-cover" alt="" />
                        </div>
                        <p class="mt-5 font-semibold text-clover-ink">{{ m.name }}</p>
                        <p class="text-sm text-clover-muted">{{ m.position }}</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
