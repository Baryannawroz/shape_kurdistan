<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/admin-route.js';

defineProps({
    counts: Object,
    recentMessages: Array,
});

const page = usePage();
const canManageSite = computed(() => page.props.canManageSite === true);
const locale = computed(() => page.props.locale ?? 'ckb');
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />
        <h1 class="text-2xl font-bold text-slate-900">Dashboard</h1>

        <div
            v-if="canManageSite"
            class="mt-4 rounded-lg border border-primary/20 bg-primary/5 p-4 text-sm text-slate-700"
        >
            <p class="font-semibold text-slate-900">Edit content on the live site</p>
            <p class="mt-1">
                Open any public page to see edit shortcuts at the bottom, or use the bar below.
                Contact details can be edited directly on the contact page.
            </p>
            <Link
                :href="r('site.home', { locale })"
                class="mt-3 inline-flex rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-dark"
            >
                View site
            </Link>
        </div>

        <div
            v-else-if="page.props.auth?.user"
            class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900"
        >
            Your account does not have CMS access yet. Ask a super-admin to assign you the
            <strong>admin</strong> or <strong>editor</strong> role.
        </div>
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
