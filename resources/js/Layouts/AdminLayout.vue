<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import AdminPreviewBar from '@/Components/Admin/AdminPreviewBar.vue';
import { r } from '@/lib/route.js';

const page = usePage();
const user = computed(() => page.props.auth?.user ?? null);
const isSuperAdmin = computed(() => user.value?.roles?.includes('super-admin') ?? false);
const hasAdminBar = computed(
    () => page.props.canManageSite === true && (page.props.adminEdits?.length ?? 0) > 0,
);

function logout() {
    router.post(r('logout'));
}
</script>

<template>
    <div class="min-h-screen bg-slate-100">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3 lg:px-8">
                <Link :href="r('admin.dashboard')" class="font-semibold text-slate-900">CMS</Link>
                <nav class="flex flex-1 flex-wrap items-center gap-3 text-sm">
                    <Link :href="r('admin.cms.pages.index')" class="text-slate-600 hover:text-slate-900">Pages</Link>
                    <Link :href="r('admin.cms.services.index')" class="text-slate-600 hover:text-slate-900">Services</Link>
                    <Link :href="r('admin.cms.product-categories.index')" class="text-slate-600 hover:text-slate-900">Categories</Link>
                    <Link :href="r('admin.cms.products.index')" class="text-slate-600 hover:text-slate-900">Products</Link>
                    <Link :href="r('admin.cms.projects.index')" class="text-slate-600 hover:text-slate-900">Projects</Link>
                    <Link :href="r('admin.cms.team-members.index')" class="text-slate-600 hover:text-slate-900">Team</Link>
                    <Link :href="r('admin.cms.testimonials.index')" class="text-slate-600 hover:text-slate-900">Testimonials</Link>
                    <Link :href="r('admin.cms.messages.index')" class="text-slate-600 hover:text-slate-900">Messages</Link>
                    <Link :href="r('admin.cms.contact-settings.edit')" class="text-slate-600 hover:text-slate-900">Contact</Link>
                    <Link :href="r('admin.cms.site-settings.index')" class="text-slate-600 hover:text-slate-900">Site settings</Link>
                    <template v-if="isSuperAdmin">
                        <a :href="r('admin.users.index')" class="text-slate-600 hover:text-slate-900">Users</a>
                        <a :href="r('admin.users.create')" class="font-medium text-primary hover:underline">Create user</a>
                    </template>
                    <Link :href="r('site.home', { locale: page.props.locale })" class="text-primary hover:underline">View site</Link>
                </nav>
                <div v-if="user" class="flex items-center gap-3 text-sm">
                    <span class="hidden text-slate-500 sm:inline">{{ user.name }}</span>
                    <button
                        type="button"
                        class="rounded-md border border-slate-200 px-3 py-1.5 text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                        @click="logout"
                    >
                        Log out
                    </button>
                </div>
            </div>
        </header>
        <main class="mx-auto max-w-7xl px-4 py-8 lg:px-8" :class="{ 'pb-20': hasAdminBar }">
            <slot />
        </main>
        <AdminPreviewBar />
    </div>
</template>
