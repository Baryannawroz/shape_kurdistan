<script setup>
import { computed, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AdminPreviewBar from '@/Components/Admin/AdminPreviewBar.vue';
import Navbar from '@/Components/Front/Navbar.vue';
import Footer from '@/Components/Front/Footer.vue';

defineProps({
    title: { type: String, default: '' },
});

const page = usePage();
const hasAdminBar = computed(
    () => page.props.canManageSite === true && (page.props.adminEdits?.length ?? 0) > 0,
);

function syncDocumentLocale() {
    if (typeof document === 'undefined') {
        return;
    }

    const rawLocale = String(page.props.locale ?? 'en');
    const lang = rawLocale.replace('_', '-');
    const dir = page.props.direction === 'rtl' ? 'rtl' : 'ltr';

    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', dir);
    document.documentElement.classList.remove('font-sans', 'font-arabic');
    document.documentElement.classList.add(dir === 'rtl' ? 'font-arabic' : 'font-sans');
}

onMounted(syncDocumentLocale);
watch(
    () => [page.props.locale, page.props.direction],
    () => syncDocumentLocale(),
);
</script>

<template>
    <div class="relative flex min-h-screen flex-col overflow-x-hidden bg-clover-bg text-clover-ink antialiased">
        <div class="pointer-events-none fixed inset-0 -z-10 bg-mesh-warm opacity-60" aria-hidden="true" />
        <Head :title="title" />
        <Navbar />
        <main class="relative flex-1" :class="{ 'pb-20': hasAdminBar }">
            <slot />
        </main>
        <Footer />
        <AdminPreviewBar />
    </div>
</template>
