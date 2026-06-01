<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    page: Object,
    locales: Array,
});

const videoUploading = ref(null);
const videoUploadError = ref(null);

const form = useForm({
    slug: props.page?.slug ?? '',
    is_active: props.page?.is_active ?? true,
    order: props.page?.order ?? 0,
    translations: props.locales.map((loc) => {
        const t = props.page?.translations?.[loc] ?? {};

        return {
            locale: loc,
            title: t.title ?? '',
            content: t.content ?? '',
            meta_title: t.meta_title ?? '',
            meta_description: t.meta_description ?? '',
        };
    }),
});

function submit() {
    if (props.page?.id) {
        form.put(r('admin.cms.pages.update', props.page.id));
    } else {
        form.post(r('admin.cms.pages.store'));
    }
}

async function uploadVideo(event, localeIndex) {
    const file = event.target.files?.[0];

    if (!file) {
        return;
    }

    videoUploading.value = localeIndex;
    videoUploadError.value = null;

    const body = new FormData();
    body.append('video', file);

    try {
        const response = await fetch(r('admin.cms.pages.upload-video'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '',
                Accept: 'application/json',
            },
            body,
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message ?? 'Upload failed.');
        }

        const shortcode = data.shortcode ?? '';
        const current = form.translations[localeIndex].content ?? '';
        form.translations[localeIndex].content = current ? `${current}\n<p>${shortcode}</p>` : `<p>${shortcode}</p>`;
    } catch (error) {
        videoUploadError.value = error instanceof Error ? error.message : 'Upload failed.';
    } finally {
        videoUploading.value = null;
        event.target.value = '';
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="page ? 'Edit page' : 'New page'" />
        <h1 class="text-2xl font-bold">{{ page ? 'Edit page' : 'New page' }}</h1>
        <form class="mt-6 space-y-6 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Slug</label>
                    <input v-model="form.slug" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    <p v-if="form.errors.slug" class="text-sm text-red-600">{{ form.errors.slug }}</p>
                </div>
                <div class="flex items-center gap-4 pt-6">
                    <label class="flex items-center gap-2 text-sm">
                        <input v-model="form.is_active" type="checkbox" />
                        Active
                    </label>
                    <div>
                        <label class="text-sm font-medium">Order</label>
                        <input v-model.number="form.order" type="number" class="mt-1 w-24 rounded border px-2 py-1 text-sm" />
                    </div>
                </div>
            </div>
            <div v-for="(row, idx) in form.translations" :key="row.locale" class="border-t pt-4">
                <h2 class="text-lg font-semibold uppercase">{{ row.locale }}</h2>
                <div class="mt-3 space-y-3">
                    <div>
                        <label class="text-sm font-medium">Title</label>
                        <input v-model="form.translations[idx].title" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Content</label>
                        <div class="mt-2 flex flex-wrap items-center gap-3">
                            <label
                                class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-slate-300 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
                            >
                                <input
                                    type="file"
                                    accept="video/mp4,video/webm,video/quicktime"
                                    class="sr-only"
                                    :disabled="videoUploading === idx"
                                    @change="uploadVideo($event, idx)"
                                />
                                {{ videoUploading === idx ? 'Uploading…' : 'Upload video (MP4)' }}
                            </label>
                            <span class="text-xs text-slate-500">Inserts a self-hosted player — no YouTube branding.</span>
                        </div>
                        <p v-if="videoUploadError" class="mt-1 text-sm text-red-600">{{ videoUploadError }}</p>
                        <TinyMceEditor v-model="form.translations[idx].content" class="mt-1" />
                    </div>
                    <div class="grid gap-3 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium">Meta title</label>
                            <input v-model="form.translations[idx].meta_title" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="text-sm font-medium">Meta description</label>
                            <input v-model="form.translations[idx].meta_description" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">Save</button>
        </form>
    </AdminLayout>
</template>
