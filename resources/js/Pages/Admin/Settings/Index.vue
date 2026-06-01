<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    settings: Object,
});

function flatten(groups) {
    const out = {};
    Object.values(groups ?? {}).forEach((group) => {
        Object.assign(out, group);
    });

    return out;
}

const form = useForm({
    settings: flatten(props.settings),
    site_logo: null,
    site_favicon: null,
    seo_og_image: null,
});

function submit() {
    form.put(r('admin.cms.site-settings.update'), { forceFormData: true });
}
</script>

<template>
    <AdminLayout>
        <Head title="Site settings" />
        <h1 class="text-2xl font-bold">Site settings</h1>
        <form class="mt-6 space-y-6 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
            <div v-for="(group, gName) in settings" :key="gName" class="border-b pb-6">
                <h2 class="text-lg font-semibold capitalize">{{ gName }}</h2>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    <div v-for="(_, key) in group" :key="key" class="md:col-span-2">
                        <label class="text-xs font-medium uppercase text-slate-500">{{ key }}</label>
                        <textarea v-model="form.settings[key]" rows="2" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-3">
                <div>
                    <label class="text-sm font-medium">Logo</label>
                    <input type="file" class="mt-1 w-full text-sm" @input="form.site_logo = $event.target.files[0]" />
                </div>
                <div>
                    <label class="text-sm font-medium">Favicon</label>
                    <input type="file" class="mt-1 w-full text-sm" @input="form.site_favicon = $event.target.files[0]" />
                </div>
                <div>
                    <label class="text-sm font-medium">OG image</label>
                    <input type="file" class="mt-1 w-full text-sm" @input="form.seo_og_image = $event.target.files[0]" />
                </div>
            </div>
            <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">Save</button>
        </form>
    </AdminLayout>
</template>
