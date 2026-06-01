<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SortableResourceList from '@/Components/Admin/SortableResourceList.vue';
import { r } from '@/lib/route.js';

defineProps({ categories: Array });
</script>

<template>
    <AdminLayout>
        <Head title="Product categories" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Product categories</h1>
            <Link :href="r('admin.cms.product-categories.create')" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">New category</Link>
        </div>
        <SortableResourceList :items="categories" reorder-route="admin.cms.product-categories.reorder" class="mt-6">
            <template #row="{ item }">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-xs text-slate-500">Order {{ item.order }}</p>
                    </div>
                    <Link :href="r('admin.cms.product-categories.edit', item.id)" class="shrink-0 text-sm font-semibold text-primary">Edit</Link>
                </div>
            </template>
        </SortableResourceList>
    </AdminLayout>
</template>
