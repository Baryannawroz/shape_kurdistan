<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SortableResourceList from '@/Components/Admin/SortableResourceList.vue';
import { r } from '@/lib/route.js';

defineProps({ projects: Array });
</script>

<template>
    <AdminLayout>
        <Head title="Projects" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Projects</h1>
            <Link :href="r('admin.cms.projects.create')" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">New project</Link>
        </div>
        <SortableResourceList :items="projects" reorder-route="admin.cms.projects.reorder" class="mt-6">
            <template #row="{ item }">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-xs text-slate-500">{{ item.category }}</p>
                    </div>
                    <Link :href="r('admin.cms.projects.edit', item.id)" class="shrink-0 text-sm font-semibold text-primary">Edit</Link>
                </div>
            </template>
        </SortableResourceList>
    </AdminLayout>
</template>
