<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();

const locales = computed(() => page.props.locales ?? {});

const currentLocale = computed(() => String(page.props.locale ?? ''));

const pathWithoutLocale = computed(() => {
    const path = page.url.split('?')[0] || '/';
    const codes = Object.keys(locales.value);
    if (codes.length === 0) {
        return path || '/';
    }
    const escaped = codes.map((c) => c.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')).join('|');
    const stripped = path.replace(new RegExp(`^\\/(${escaped})(?=\\/|$)`), '');
    return stripped === '' ? '/' : stripped;
});
</script>

<template>
    <div
        class="flex items-center gap-1 rounded-full border border-clover-border bg-clover-bg px-1 py-0.5 text-xs"
        role="group"
        aria-label="Language"
    >
        <Link
            v-for="code in Object.keys(locales)"
            :key="code"
            :href="`/${code}${pathWithoutLocale === '/' ? '' : pathWithoutLocale}`"
            class="rounded-full px-2.5 py-1 font-semibold tracking-wide transition"
            :class="
                code === currentLocale
                    ? 'bg-clover-dark text-white'
                    : 'text-clover-muted hover:bg-black/[0.04] hover:text-clover-ink'
            "
        >
            {{ String(code).toUpperCase() }}
        </Link>
    </div>
</template>
