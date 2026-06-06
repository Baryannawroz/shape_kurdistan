<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SortableResourceList from '@/Components/Admin/SortableResourceList.vue';
import { r } from '@/lib/admin-route.js';

defineProps({ products: Array });
</script>

<template>
    <AdminLayout>
        <Head title="Products" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Products</h1>
            <Link :href="r('admin.cms.products.create')" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">New product</Link>
        </div>
        <SortableResourceList :items="products" reorder-route="admin.cms.products.reorder" class="mt-6">
            <template #row="{ item }">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ item.title }}</p>
                        <p class="text-xs text-slate-500">{{ item.category_title }} · order {{ item.order }}</p>
                    </div>
                    <Link :href="r('admin.cms.products.edit', item.id)" class="shrink-0 text-sm font-semibold text-primary">Edit</Link>
                </div>
            </template>
        </SortableResourceList>
    </AdminLayout>
</template>
