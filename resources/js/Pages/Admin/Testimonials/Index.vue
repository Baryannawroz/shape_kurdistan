<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import SortableResourceList from '@/Components/Admin/SortableResourceList.vue';
import { r } from '@/lib/route.js';

defineProps({ testimonials: Array });
</script>

<template>
    <AdminLayout>
        <Head title="Testimonials" />
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Testimonials</h1>
            <Link :href="r('admin.cms.testimonials.create')" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white">New testimonial</Link>
        </div>
        <SortableResourceList :items="testimonials" reorder-route="admin.cms.testimonials.reorder" class="mt-6">
            <template #row="{ item }">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="font-medium">{{ item.author }}</p>
                        <p class="text-xs text-slate-500">Rating {{ item.rating }}</p>
                    </div>
                    <Link :href="r('admin.cms.testimonials.edit', item.id)" class="shrink-0 text-sm font-semibold text-primary">Edit</Link>
                </div>
            </template>
        </SortableResourceList>
    </AdminLayout>
</template>
