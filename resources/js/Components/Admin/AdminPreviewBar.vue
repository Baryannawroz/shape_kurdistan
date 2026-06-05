<script setup>
import { computed } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { r } from '@/lib/route.js';

const page = usePage();

const canManageSite = computed(() => page.props.canManageSite === true);
const adminEdits = computed(() => page.props.adminEdits ?? []);
const user = computed(() => page.props.auth?.user ?? null);

function logout() {
    router.post(r('logout'));
}
</script>

<template>
    <div
        v-if="canManageSite && adminEdits.length"
        class="fixed inset-x-0 bottom-0 z-50 border-t border-slate-700 bg-slate-900/95 text-white shadow-lg backdrop-blur"
    >
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-3 px-4 py-3 lg:px-8">
            <div class="flex min-w-0 flex-1 flex-wrap items-center gap-2">
                <span class="shrink-0 text-xs font-semibold uppercase tracking-wide text-slate-400">Admin</span>
                <a
                    v-for="edit in adminEdits"
                    :key="edit.href"
                    :href="edit.href"
                    class="rounded-md px-3 py-1.5 text-sm font-medium transition"
                    :class="edit.primary
                        ? 'bg-primary text-white hover:bg-primary-dark'
                        : 'bg-slate-800 text-slate-100 hover:bg-slate-700'"
                >
                    {{ edit.label }}
                </a>
            </div>
            <div class="flex shrink-0 items-center gap-2 text-sm">
                <span v-if="user" class="hidden text-slate-400 sm:inline">{{ user.name }}</span>
                <a
                    :href="r('admin.dashboard')"
                    class="rounded-md border border-slate-600 px-3 py-1.5 text-slate-200 transition hover:bg-slate-800"
                >
                    CMS
                </a>
                <button
                    type="button"
                    class="rounded-md border border-slate-600 px-3 py-1.5 text-slate-200 transition hover:bg-slate-800"
                    @click="logout"
                >
                    Log out
                </button>
            </div>
        </div>
    </div>
</template>
