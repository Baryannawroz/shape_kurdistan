<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import { r } from '@/lib/admin-route.js';

const props = defineProps({
    testimonial: Object,
    locales: Array,
});

const form = useForm({
    avatar: null,
    rating: props.testimonial?.rating ?? 5,
    is_active: props.testimonial?.is_active ?? true,
    order: props.testimonial?.order ?? 0,
    translations: props.locales.map((loc) => {
        const t = props.testimonial?.translations?.[loc] ?? {};

        return {
            locale: loc,
            author_name: t.author_name ?? '',
            company: t.company ?? '',
            content: t.content ?? '',
        };
    }),
});

function submit() {
    if (props.testimonial?.id) {
        form.put(r('admin.cms.testimonials.update', props.testimonial.id), { forceFormData: true });
    } else {
        form.post(r('admin.cms.testimonials.store'), { forceFormData: true });
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="testimonial ? 'Edit testimonial' : 'New testimonial'" />
        <h1 class="text-2xl font-bold">{{ testimonial ? 'Edit testimonial' : 'New testimonial' }}</h1>
        <form class="mt-6 space-y-6 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Avatar</label>
                    <input type="file" class="mt-1 w-full text-sm" @input="form.avatar = $event.target.files[0]" />
                </div>
                <div>
                    <label class="text-sm font-medium">Rating</label>
                    <select v-model.number="form.rating" class="mt-1 w-full rounded border px-3 py-2 text-sm">
                        <option v-for="n in 5" :key="n" :value="n">{{ n }}</option>
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input v-model="form.is_active" type="checkbox" />
                    Active
                </label>
                <div>
                    <label class="text-sm font-medium">Order</label>
                    <input v-model.number="form.order" type="number" class="mt-1 w-24 rounded border px-2 py-1 text-sm" />
                </div>
            </div>
            <div v-for="(row, idx) in form.translations" :key="row.locale" class="border-t pt-4">
                <h2 class="text-lg font-semibold uppercase">{{ row.locale }}</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium">Author</label>
                        <input v-model="form.translations[idx].author_name" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Company</label>
                        <input v-model="form.translations[idx].company" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Content</label>
                        <TinyMceEditor v-model="form.translations[idx].content" class="mt-1" />
                    </div>
                </div>
            </div>
            <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">Save</button>
        </form>
    </AdminLayout>
</template>
