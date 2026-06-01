<script setup>
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import PageHero from '@/Components/Front/PageHero.vue';
import ContactForm from '@/Components/Front/ContactForm.vue';

defineProps({
    mapsEmbedUrl: String,
    contact: {
        type: Object,
        default: () => ({
            phone: null,
            email: null,
            address: null,
        }),
    },
});
</script>

<template>
    <AppLayout title="Contact">
        <Head title="Contact" />
        <PageHero
            eyebrow="Let's talk"
            title="Contact"
            lead="Tell us about your project — we will respond as soon as we can."
        />
        <section class="mx-auto max-w-6xl px-4 py-16 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                <div>
                    <h2 class="text-lg font-semibold text-clover-ink">Details</h2>
                    <p class="mt-2 text-clover-muted">Prefer email or phone? Use whichever is easiest.</p>
                    <dl v-if="contact.phone || contact.email || contact.address" class="mt-8 space-y-6 text-clover-muted">
                        <div v-if="contact.phone">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-clover-muted">Phone</dt>
                            <dd class="mt-1 text-lg">
                                <a
                                    :href="'tel:' + String(contact.phone).replace(/\s/g, '')"
                                    class="font-medium text-primary transition hover:text-primary-dark"
                                >
                                    {{ contact.phone }}
                                </a>
                            </dd>
                        </div>
                        <div v-if="contact.email">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-clover-muted">Email</dt>
                            <dd class="mt-1 text-lg">
                                <a :href="'mailto:' + contact.email" class="font-medium text-primary transition hover:text-primary-dark">
                                    {{ contact.email }}
                                </a>
                            </dd>
                        </div>
                        <div v-if="contact.address">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-clover-muted">Address</dt>
                            <dd class="mt-1 whitespace-pre-line">{{ contact.address }}</dd>
                        </div>
                    </dl>
                    <div v-if="mapsEmbedUrl" class="mt-10 aspect-video overflow-hidden rounded-clover border border-clover-border bg-clover-bg shadow-inner">
                        <iframe :src="mapsEmbedUrl" class="h-full w-full border-0" loading="lazy" title="Map" />
                    </div>
                </div>
                <div class="clover-card p-8 lg:p-10">
                    <h2 class="text-lg font-semibold text-clover-ink">Send a message</h2>
                    <p class="mt-1 text-sm text-clover-muted">Fields marked required must be completed.</p>
                    <div class="mt-6">
                        <ContactForm />
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
