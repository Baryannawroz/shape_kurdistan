<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    member: Object,
    locales: Array,
});

const form = useForm({
    photo: null,
    role_key: props.member?.role_key ?? '',
    linkedin: props.member?.linkedin ?? '',
    is_active: props.member?.is_active ?? true,
    order: props.member?.order ?? 0,
    translations: props.locales.map((loc) => {
        const t = props.member?.translations?.[loc] ?? {};

        return {
            locale: loc,
            name: t.name ?? '',
            position: t.position ?? '',
            bio: t.bio ?? '',
        };
    }),
});

function submit() {
    if (props.member?.id) {
        form.put(r('admin.cms.team-members.update', props.member.id), { forceFormData: true });
    } else {
        form.post(r('admin.cms.team-members.store'), { forceFormData: true });
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="member ? 'Edit team member' : 'New team member'" />
        <h1 class="text-2xl font-bold">{{ member ? 'Edit team member' : 'New team member' }}</h1>
        <form class="mt-6 space-y-6 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium">Photo</label>
                    <input type="file" class="mt-1 w-full text-sm" @input="form.photo = $event.target.files[0]" />
                </div>
                <div>
                    <label class="text-sm font-medium">LinkedIn</label>
                    <input v-model="form.linkedin" type="url" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium">Role key</label>
                    <input v-model="form.role_key" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
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
                        <label class="text-sm font-medium">Name</label>
                        <input v-model="form.translations[idx].name" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Position</label>
                        <input v-model="form.translations[idx].position" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Bio</label>
                        <TinyMceEditor v-model="form.translations[idx].bio" class="mt-1" />
                    </div>
                </div>
            </div>
            <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing">Save</button>
        </form>
    </AdminLayout>
</template>
