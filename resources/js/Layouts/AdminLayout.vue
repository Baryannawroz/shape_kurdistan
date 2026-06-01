<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { r } from '@/lib/route.js';

const page = usePage();
</script>

<template>
    <div class="min-h-screen bg-slate-100">
        <header class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 lg:px-8">
                <Link :href="r('admin.dashboard')" class="font-semibold text-slate-900">CMS</Link>
                <nav class="flex flex-wrap gap-3 text-sm">
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
                    <Link :href="r('site.home', { locale: page.props.locale })" class="text-primary hover:underline">View site</Link>
                </nav>
            </div>
        </header>
        <main class="mx-auto max-w-7xl px-4 py-8 lg:px-8">
            <slot />
        </main>
    </div>
</template>
