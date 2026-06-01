<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    product: Object,
    categories: Array,
    locales: Array,
});

const form = useForm({
    product_category_id: props.product?.product_category_id ?? (props.categories[0]?.id ?? ''),
    image: null,
    sku: props.product?.sku ?? '',
    price: props.product?.price ?? '',
    is_active: props.product?.is_active ?? true,
    order: props.product?.order ?? 0,
    translations: props.locales.map((loc) => {
        const t = props.product?.translations?.[loc] ?? {};

        return {
            locale: loc,
            slug: t.slug ?? '',
            title: t.title ?? '',
            description: t.description ?? '',
        };
    }),
});

function submit() {
    if (props.product?.id) {
        form.put(r('admin.cms.products.update', props.product.id), { forceFormData: true });
    } else {
        form.post(r('admin.cms.products.store'), { forceFormData: true });
    }
}
</script>

<template>
    <AdminLayout>
        <Head :title="product ? 'Edit product' : 'New product'" />
        <h1 class="text-2xl font-bold">{{ product ? 'Edit product' : 'New product' }}</h1>
        <form class="mt-6 space-y-6 rounded-lg bg-white p-6 shadow" @submit.prevent="submit">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="text-sm font-medium">Category</label>
                    <select v-model="form.product_category_id" class="mt-1 w-full max-w-md rounded border px-3 py-2 text-sm" required>
                        <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.label }}</option>
                    </select>
                    <p v-if="form.errors.product_category_id" class="mt-1 text-sm text-red-600">{{ form.errors.product_category_id }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium">SKU</label>
                    <input v-model="form.sku" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium">Price</label>
                    <input v-model="form.price" type="number" step="0.01" min="0" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                </div>
                <div>
                    <label class="text-sm font-medium">Image</label>
                    <input type="file" class="mt-1 w-full text-sm" accept="image/*" @input="form.image = $event.target.files[0]" />
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input v-model="form.is_active" type="checkbox" />
                    Active
                </label>
                <div>
                    <label class="text-sm font-medium">Order</label>
                    <input v-model.number="form.order" type="number" class="mt-1 w-24 rounded border px-2 py-1 text-sm" />
                </div>
            </div>
            <div v-for="(row, idx) in form.translations" :key="row.locale" class="border-t pt-4">
                <h2 class="text-lg font-semibold uppercase">{{ row.locale }}</h2>
                <div class="mt-3 grid gap-3 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium">Slug</label>
                        <input v-model="form.translations[idx].slug" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-sm font-medium">Title</label>
                        <input v-model="form.translations[idx].title" type="text" class="mt-1 w-full rounded border px-3 py-2 text-sm" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Description</label>
                        <TinyMceEditor v-model="form.translations[idx].description" class="mt-1" />
                    </div>
                </div>
            </div>
            <p v-if="! categories.length" class="text-sm text-amber-700">Create at least one product category before saving products.</p>
            <button type="submit" class="rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white" :disabled="form.processing || ! categories.length">Save</button>
        </form>
    </AdminLayout>
</template>
