<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/route.js';

defineProps({
    counts: Object,
    recentMessages: Array,
});
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Projects</p>
                <p class="text-2xl font-bold">{{ counts.projects }}</p>
            </div>
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Services</p>
                <p class="text-2xl font-bold">{{ counts.services }}</p>
            </div>
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Categories</p>
                <p class="text-2xl font-bold">{{ counts.product_categories }}</p>
            </div>
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Products</p>
                <p class="text-2xl font-bold">{{ counts.products }}</p>
            </div>
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Team</p>
                <p class="text-2xl font-bold">{{ counts.team }}</p>
            </div>
            <div class="rounded-lg bg-white p-4 shadow">
                <p class="text-sm text-slate-500">Unread messages</p>
                <p class="text-2xl font-bold">{{ counts.unread_messages }}</p>
            </div>
        </div>
        <div class="mt-10 rounded-lg bg-white p-6 shadow">
            <h2 class="text-lg font-semibold">Recent messages</h2>
            <table class="mt-4 w-full text-left text-sm">
                <thead>
                    <tr class="border-b text-slate-500">
                        <th class="py-2">Name</th>
                        <th>Subject</th>
                        <th>When</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="m in recentMessages" :key="m.id" class="border-b">
                        <td class="py-2">
                            <Link :href="r('admin.cms.messages.show', { message: m.id })" class="text-primary hover:underline">{{ m.name }}</Link>
                        </td>
                        <td>{{ m.subject }}</td>
                        <td class="text-slate-500">{{ m.created_at }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
