<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { r } from '@/lib/route.js';

defineProps({ pages: Array });
</script>

<template>
    <AdminLayout>
        <Head title="Pages" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Pages</h1>
            <Link :href="r('admin.cms.pages.create')" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">New page</Link>
        </div>
        <ul class="mt-6 divide-y rounded-lg bg-white shadow">
            <li v-for="p in pages" :key="p.id" class="flex items-center justify-between px-4 py-3">
                <div>
                    <p class="font-medium">{{ p.title }}</p>
                    <p class="text-xs text-slate-500">{{ p.slug }}</p>
                </div>
                <Link :href="r('admin.cms.pages.edit', p.id)" class="text-sm font-semibold text-primary">Edit</Link>
            </li>
        </ul>
    </AdminLayout>
</template>
