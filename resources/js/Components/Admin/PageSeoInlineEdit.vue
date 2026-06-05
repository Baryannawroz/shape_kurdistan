<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import SeoFields from '@/Components/Admin/SeoFields.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    pageKey: {
        type: String,
        required: true,
    },
    seoSettings: {
        type: Object,
        required: true,
    },
    localeMeta: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const open = ref(false);

const form = useForm({
    settings: { ...props.seoSettings },
});

function submit() {
    form.put(r('admin.cms.site-settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <div v-if="page.props.canManageSite" class="mb-8">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-amber-300/60 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-900 transition hover:bg-amber-100"
            @click="open = !open"
        >
            <span>{{ open ? 'Close SEO editor' : 'Edit Google search text' }}</span>
        </button>

        <form
            v-if="open"
            class="mt-4 space-y-6 rounded-clover border border-clover-border bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div v-for="loc in localeMeta" :key="loc.code" class="space-y-4 border-t border-clover-border pt-4 first:border-t-0 first:pt-0">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-clover-ink">{{ loc.name }}</h3>
                <SeoFields
                    v-model:title="form.settings['seo.page.' + pageKey + '.title_' + loc.code]"
                    v-model:description="form.settings['seo.page.' + pageKey + '.description_' + loc.code]"
                    :keywords="loc.code === localeMeta[0]?.code ? form.settings['seo.keywords'] : ''"
                    @update:keywords="(value) => { if (loc.code === localeMeta[0]?.code) form.settings['seo.keywords'] = value; }"
                />
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-dark disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save SEO
                </button>
                <button
                    type="button"
                    class="rounded-lg border border-clover-border px-4 py-2 text-sm font-medium text-clover-muted transition hover:bg-clover-bg"
                    @click="open = false"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>
