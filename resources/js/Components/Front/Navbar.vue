<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { r } from '@/lib/route.js';
import LanguageSwitcher from '@/Components/Front/LanguageSwitcher.vue';

const page = usePage();
const navLinks = page.props.navLinks ?? [];
const locale = computed(() => String(page.props.locale ?? 'en'));
const menuOpen = ref(false);

const isRtl = computed(() => page.props.direction === 'rtl');

const ziggyBase = computed(() => (page.props.ziggy && typeof page.props.ziggy === 'object' ? page.props.ziggy.url : '') || '');

const currentPath = computed(() => {
    const u = page.url.split('?')[0] ?? '';
    return u.replace(/\/+$/, '') || '/';
});

function itemPath(item) {
    const href = r(item.route, { locale: locale.value, ...item.params });
    try {
        const base = typeof window !== 'undefined' ? window.location.origin : ziggyBase.value;
        return new URL(href, base || 'http://localhost').pathname.replace(/\/+$/, '') || '/';
    } catch {
        return href;
    }
}

function isActive(item) {
    const p = itemPath(item);
    const cur = currentPath.value;
    const home = `/${locale.value}`;
    if (p === home) {
        return cur === home;
    }
    return cur === p || cur.startsWith(`${p}/`);
}

function closeMenu() {
    menuOpen.value = false;
}

const primaryNav = computed(() => navLinks.filter((item) => item.route !== 'site.home'));
</script>

<template>
    <header class="sticky top-0 z-50 px-4 pt-4 lg:px-8">
        <div
            class="mx-auto flex max-w-6xl items-center justify-between gap-3 rounded-full border border-clover-border/80 bg-white/90 px-4 py-2.5 shadow-nav-pill backdrop-blur-xl"
            :class="{ 'flex-row-reverse': isRtl }"
        >
            <Link
                :href="r('site.home', { locale })"
                class="group flex shrink-0 items-center gap-2.5"
                @click="closeMenu"
            >
                <span
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-clover-dark text-sm font-bold text-white transition group-hover:bg-neutral-800"
                    aria-hidden="true"
                >
                    {{ String(page.props.siteName || 'S').charAt(0) }}
                </span>
                <span class="max-w-[9rem] truncate text-sm font-semibold text-clover-ink sm:max-w-none">
                    {{ page.props.siteName }}
                </span>
            </Link>

            <nav class="hidden items-center gap-0.5 lg:flex">
                <Link
                    v-for="item in primaryNav"
                    :key="item.route"
                    :href="r(item.route, { locale, ...item.params })"
                    :class="isActive(item) ? 'clover-nav-link-active' : 'clover-nav-link'"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <div class="flex items-center gap-2">
                <LanguageSwitcher class="hidden sm:flex" />
                <Link
                    :href="r('site.contact', { locale })"
                    class="clover-btn-primary hidden px-5 py-2.5 text-xs sm:inline-flex md:text-sm"
                >
                    Get Started
                </Link>
                <button
                    type="button"
                    class="inline-flex rounded-full border border-clover-border p-2 text-clover-ink transition hover:bg-black/[0.04] lg:hidden"
                    :aria-expanded="menuOpen"
                    aria-controls="mobile-nav"
                    @click="menuOpen = !menuOpen"
                >
                    <span class="sr-only">Toggle menu</span>
                    <Bars3Icon v-if="!menuOpen" class="h-6 w-6" />
                    <XMarkIcon v-else class="h-6 w-6" />
                </button>
            </div>
        </div>

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 -translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-1"
        >
            <div
                v-show="menuOpen"
                id="mobile-nav"
                class="mx-auto mt-2 max-w-6xl rounded-2xl border border-clover-border bg-white p-4 shadow-nav-pill lg:hidden"
            >
                <nav class="flex flex-col gap-1">
                    <Link
                        v-for="item in navLinks"
                        :key="`m-${item.route}`"
                        :href="r(item.route, { locale, ...item.params })"
                        class="rounded-xl px-4 py-3 text-base font-medium transition"
                        :class="isActive(item) ? 'bg-clover-dark text-white' : 'text-clover-muted hover:bg-black/[0.04] hover:text-clover-ink'"
                        @click="closeMenu"
                    >
                        {{ item.label }}
                    </Link>
                </nav>
                <div class="mt-3 border-t border-clover-border pt-3">
                    <LanguageSwitcher />
                </div>
                <Link
                    :href="r('site.contact', { locale })"
                    class="clover-btn-primary mt-3 w-full"
                    @click="closeMenu"
                >
                    Get Started
                </Link>
            </div>
        </Transition>
    </header>
</template>
