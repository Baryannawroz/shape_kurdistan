<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import TinyMceEditor from '@/Components/Admin/TinyMceEditor.vue';
import { r } from '@/lib/route.js';

const props = defineProps({
    contactSettings: {
        type: Object,
        required: true,
    },
    localeMeta: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const open = ref(false);

const form = useForm({
    phone: props.contactSettings.phone ?? '',
    email: props.contactSettings.email ?? '',
    maps_embed_url: props.contactSettings.maps_embed_url ?? '',
    address: { ...props.contactSettings.address },
});

function submit() {
    form.put(r('admin.cms.contact-settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <div v-if="page.props.canManageSite" class="mb-8">
        <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-primary/30 bg-primary/5 px-4 py-2 text-sm font-semibold text-primary transition hover:bg-primary/10"
            @click="open = !open"
        >
            <span>{{ open ? 'Close editor' : 'Edit contact details' }}</span>
        </button>

        <form
            v-if="open"
            class="mt-4 space-y-6 rounded-clover border border-clover-border bg-white p-6 shadow-sm"
            @submit.prevent="submit"
        >
            <p class="text-sm text-clover-muted">
                Changes appear on this page and in the site footer. Use full contact settings in CMS for advanced options.
            </p>

            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-clover-ink">Phone</label>
                    <input v-model="form.phone" type="text" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
                    <p v-if="form.errors.phone" class="mt-1 text-sm text-red-600">{{ form.errors.phone }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-clover-ink">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">{{ form.errors.email }}</p>
                </div>
            </div>

            <div v-for="loc in localeMeta" :key="loc.code">
                <label class="text-sm font-medium text-clover-ink">Address ({{ loc.name }})</label>
                <TinyMceEditor
                    v-model="form.address[loc.code]"
                    class="mt-2"
                    variant="basic"
                    :min-height="140"
                />
                <p v-if="form.errors['address.' + loc.code]" class="mt-1 text-sm text-red-600">{{ form.errors['address.' + loc.code] }}</p>
            </div>

            <div>
                <label class="text-sm font-medium text-clover-ink">Google Maps embed URL</label>
                <textarea
                    v-model="form.maps_embed_url"
                    rows="3"
                    class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 font-mono text-xs"
                />
                <p v-if="form.errors.maps_embed_url" class="mt-1 text-sm text-red-600">{{ form.errors.maps_embed_url }}</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-primary-dark disabled:opacity-60"
                    :disabled="form.processing"
                >
                    Save contact details
                </button>
                <button
                    type="button"
                    class="rounded-lg border border-clover-border px-4 py-2 text-sm font-medium text-clover-muted transition hover:bg-clover-bg"
                    @click="open = false"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>
