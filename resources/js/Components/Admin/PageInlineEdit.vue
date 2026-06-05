<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import SeoFields from '@/Components/Admin/SeoFields.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    page: {
        type: Object,
        required: true,
    },
    localeMeta: {
        type: Array,
        default: () => [],
    },
    label: {
        type: String,
        default: 'Edit page',
    },
    contentVariant: {
        type: String,
        default: 'full',
        validator: (value) => ['full', 'basic'].includes(value),
    },
});

const inertiaPage = usePage();
const open = ref(false);

const form = useForm({
    slug: props.page.slug,
    is_active: props.page.is_active,
    order: props.page.order,
    translations: props.localeMeta.map((loc) => {
        const row = props.page.translations?.[loc.code] ?? {};

        return {
            locale: loc.code,
            title: row.title ?? '',
            content: row.content ?? '',
            meta_title: row.meta_title ?? '',
            meta_description: row.meta_description ?? '',
        };
    }),
});

function submit() {
    form.put(r('admin.cms.pages.update', props.page.id), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <div v-if="inertiaPage.props.canManageSite" class="mb-8">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-primary/30 bg-primary/5 px-4 py-2 text-sm font-semibold text-primary transition hover:bg-primary/10"
            @click="open = !open"
        >
            <span>{{ open ? 'Close editor' : label }}</span>
        </button>

        <form
            v-if="open"
            class="mt-4 space-y-6 rounded-clover border border-clover-border bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <div v-for="(row, idx) in form.translations" :key="row.locale" class="space-y-4 border-t border-clover-border pt-4 first:border-t-0 first:pt-0">
                <h3 class="text-sm font-semibold uppercase tracking-wide text-clover-ink">
                    {{ localeMeta.find((loc) => loc.code === row.locale)?.name ?? row.locale }}
                </h3>
                <div>
                    <label class="text-sm font-medium text-clover-ink">Title</label>
                    <input
                        v-model="form.translations[idx].title"
                        type="text"
                        class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm"
                    />
                    <p v-if="form.errors['translations.' + idx + '.title']" class="mt-1 text-sm text-red-600">
                        {{ form.errors['translations.' + idx + '.title'] }}
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-clover-ink">{{ contentVariant === 'full' ? 'Content' : 'Intro text' }}</label>
                    <TinyMceEditor
                        v-model="form.translations[idx].content"
                        class="mt-2"
                        :variant="contentVariant"
                        :min-height="contentVariant === 'basic' ? 160 : 280"
                    />
                    <p v-if="form.errors['translations.' + idx + '.content']" class="mt-1 text-sm text-red-600">
                        {{ form.errors['translations.' + idx + '.content'] }}
                    </p>
                </div>
                <SeoFields
                    v-model:title="form.translations[idx].meta_title"
                    v-model:description="form.translations[idx].meta_description"
                />
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-dark disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save
                </button>
                <a
                    :href="r('admin.cms.pages.edit', page.id)"
                    class="rounded-lg border border-clover-border px-4 py-2 text-sm font-medium text-clover-muted transition hover:bg-clover-bg"
                >
                    Full page editor
                </a>
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
