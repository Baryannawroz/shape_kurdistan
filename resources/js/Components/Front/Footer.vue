<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import RichContent from '@/Components/Front/RichContent.vue';
import { r } from '@/lib/route.js';

const page = usePage();
const footer = page.props.footerData ?? {};
const social = page.props.socialLinks ?? {};
const locale = page.props.locale;
const navLinks = page.props.navLinks ?? [];
</script>

<template>
    <footer class="relative mt-auto border-t border-clover-border bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 lg:px-8">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <Link :href="r('site.home', { locale })" class="inline-flex items-center gap-2.5">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-clover-dark text-sm font-bold text-white"
                            aria-hidden="true"
                        >
                            {{ String(page.props.siteName || 'S').charAt(0) }}
                        </span>
                        <span class="text-lg font-semibold text-clover-ink">{{ page.props.siteName }}</span>
                    </Link>
                    <RichContent
                        v-if="footer.address"
                        :html="footer.address"
                        prose-class="prose prose-sm mt-4 max-w-sm text-clover-muted prose-strong:text-clover-ink"
                    />
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-clover-muted">Explore</p>
                    <ul class="mt-4 space-y-2.5 text-sm">
                        <li v-for="item in navLinks" :key="item.route">
                            <Link
                                :href="r(item.route, { locale, ...item.params })"
                                class="text-clover-muted transition hover:text-clover-ink"
                            >
                                {{ item.label }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-clover-muted">Contact</p>
                    <ul class="mt-4 space-y-2 text-sm text-clover-muted">
                        <li v-if="footer.phone">
                            <a :href="'tel:' + String(footer.phone).replace(/\s/g, '')" class="hover:text-clover-ink">
                                {{ footer.phone }}
                            </a>
                        </li>
                        <li v-if="footer.email">
                            <a :href="'mailto:' + footer.email" class="break-all hover:text-clover-ink">
                                {{ footer.email }}
                            </a>
                        </li>
                    </ul>
                    <div v-if="social.facebook || social.linkedin || social.twitter || social.instagram" class="mt-5 flex flex-wrap gap-2">
                        <a
                            v-if="social.facebook"
                            :href="social.facebook"
                            class="rounded-full border border-clover-border px-3 py-1 text-xs font-medium text-clover-muted transition hover:border-neutral-300 hover:text-clover-ink"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Facebook
                        </a>
                        <a
                            v-if="social.linkedin"
                            :href="social.linkedin"
                            class="rounded-full border border-clover-border px-3 py-1 text-xs font-medium text-clover-muted transition hover:border-neutral-300 hover:text-clover-ink"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            LinkedIn
                        </a>
                        <a
                            v-if="social.twitter"
                            :href="social.twitter"
                            class="rounded-full border border-clover-border px-3 py-1 text-xs font-medium text-clover-muted transition hover:border-neutral-300 hover:text-clover-ink"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            X
                        </a>
                        <a
                            v-if="social.instagram"
                            :href="social.instagram"
                            class="rounded-full border border-clover-border px-3 py-1 text-xs font-medium text-clover-muted transition hover:border-neutral-300 hover:text-clover-ink"
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            Instagram
                        </a>
                    </div>
                </div>
            </div>

            <div class="clover-divider mt-12" />

            <div class="mt-6 flex flex-col items-center justify-between gap-4 text-center text-xs text-clover-muted sm:flex-row sm:text-start">
                <p>© {{ new Date().getFullYear() }} {{ page.props.siteName }}</p>
                <Link :href="r('site.contact', { locale })" class="clover-btn-green px-5 py-2 text-xs">
                    Get Started
                </Link>
            </div>
        </div>
    </footer>
</template>
