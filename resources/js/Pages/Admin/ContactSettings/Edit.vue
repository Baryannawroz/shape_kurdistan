<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    contact: {
        type: Object,
        required: true,
    },
    localeMeta: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    phone: props.contact.phone ?? '',
    email: props.contact.email ?? '',
    maps_embed_url: props.contact.maps_embed_url ?? '',
    address: { ...props.contact.address },
});

function submit() {
    form.put(r('admin.cms.contact-settings.update'));
}
</script>

<template>
    <AdminLayout>
        <Head title="Contact settings" />
        <h1 class="text-2xl font-bold">Contact settings</h1>
        <p class="mt-2 text-sm text-slate-600">
            These details appear in the site footer and on the public contact page (for the matching language).
        </p>
        <form class="mt-6 space-y-6 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Phone</label>
                    <input v-model="form.phone" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" autocomplete="tel" />
                    <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 w-full rounded border px-3 py-2 text-sm" autocomplete="email" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
            </div>

            <div v-for="loc in localeMeta" :key="loc.code">
                <label class="text-sm font-medium">Address ({{ loc.name }})</label>
                <textarea
                    v-model="form.address[loc.code]"
                    rows="3"
                    class="mt-1 w-full rounded border px-3 py-2 text-sm"
                />
                <p v-if="form.errors['address.' + loc.code]" class="mt-1 text-sm text-red-600">{{ form.errors['address.' + loc.code] }}</p>
            </div>

            <div>
                <label class="text-sm font-medium">Google Maps embed URL</label>
                <textarea
                    v-model="form.maps_embed_url"
                    rows="3"
                    class="mt-1 w-full rounded border px-3 py-2 font-mono text-xs"
                    placeholder="https://www.google.com/maps/embed?..."
                />
                <p v-if="form.errors.maps_embed_url" class="mt-1 text-sm text-red-600">{{ form.errors.maps_embed_url }}</p>
                <p class="mt-1 text-xs text-slate-500">Paste the full iframe <code class="rounded bg-slate-100 px-1">src</code> URL from Google Maps → Share → Embed map.</p>
            </div>

            <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">Save</button>
        </form>
    </AdminLayout>
</template>
