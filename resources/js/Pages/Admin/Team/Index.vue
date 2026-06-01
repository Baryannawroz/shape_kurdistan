<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SortableResourceList from '@/Components/Admin/SortableResourceList.vue';
import { r } from '@/lib/route.js';

defineProps({ members: Array });
</script>

<template>
    <AdminLayout>
        <Head title="Team" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Team</h1>
            <Link :href="r('admin.cms.team-members.create')" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">New member</Link>
        </div>
        <SortableResourceList :items="members" reorder-route="admin.cms.team-members.reorder" class="mt-6">
            <template #row="{ item }">
                <div class="flex items-center justify-between gap-3">
                    <p class="font-medium">{{ item.name }}</p>
                    <Link :href="r('admin.cms.team-members.edit', item.id)" class="shrink-0 text-sm font-semibold text-primary">Edit</Link>
                </div>
            </template>
        </SortableResourceList>
    </AdminLayout>
</template>
