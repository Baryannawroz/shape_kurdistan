<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/admin-route.js';

defineProps({ message: Object });

function destroy(id) {
    if (confirm('Delete this message?')) {
        router.delete(r('admin.cms.messages.destroy', { message: id }));
    }
}
</script>

<template>
    <AdminLayout>
        <Head title="Message" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Message</h1>
            <div class="flex gap-3">
                <Link :href="r('admin.cms.messages.index')" class="text-sm text-primary">Back</Link>
                <button type="button" class="text-sm text-red-600" @click="destroy(message.id)">Delete</button>
            </div>
        </div>
        <div class="mt-6 rounded-lg bg-white p-6 shadow">
            <p class="text-sm text-slate-500">{{ message.created_at }}</p>
            <p class="mt-2 font-semibold">{{ message.name }} &lt;{{ message.email }}&gt;</p>
            <p v-if="message.phone" class="text-sm">{{ message.phone }}</p>
            <p class="mt-4 font-medium">{{ message.subject }}</p>
            <p class="mt-4 whitespace-pre-wrap text-slate-700">{{ message.body }}</p>
        </div>
    </AdminLayout>
</template>
