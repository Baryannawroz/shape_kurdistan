<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/route.js';

defineProps({ messages: Object });
</script>

<template>
    <AdminLayout>
        <Head title="Messages" />
        <h1 class="text-2xl font-bold">Messages</h1>
        <div class="mt-6 overflow-hidden rounded-lg bg-white shadow">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Read</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="m in messages.data" :key="m.id" class="border-t">
                        <td class="px-4 py-2">
                            <Link :href="r('admin.cms.messages.show', { message: m.id })" class="text-primary hover:underline">{{ m.name }}</Link>
                        </td>
                        <td class="px-4 py-2">{{ m.email }}</td>
                        <td class="px-4 py-2">{{ m.subject }}</td>
                        <td class="px-4 py-2">{{ m.is_read ? 'Yes' : 'No' }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="messages.links?.length > 3" class="flex gap-2 border-t px-4 py-3 text-sm">
                <Link v-for="l in messages.links" :key="l.label" :href="l.url || '#'" class="rounded px-2 py-1 hover:bg-slate-100" :class="{ 'font-semibold text-primary': l.active }" v-html="l.label" />
            </div>
        </div>
    </AdminLayout>
</template>
