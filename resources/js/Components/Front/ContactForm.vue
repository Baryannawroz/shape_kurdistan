<script setup>
import { useForm } from '@inertiajs/vue3';
import { r } from '@/lib/route.js';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const locale = page.props.locale;

const form = useForm({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
});

function submit() {
    form.post(r('site.contact.store', { locale }), { preserveScroll: true });
}
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <div>
            <label class="block text-sm font-semibold text-clover-ink">Name</label>
            <input v-model="form.name" type="text" class="clover-input" required />
            <p v-if="form.errors.name" class="mt-1.5 text-sm text-red-600">{{ form.errors.name }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-clover-ink">Email</label>
            <input v-model="form.email" type="email" class="clover-input" required />
            <p v-if="form.errors.email" class="mt-1.5 text-sm text-red-600">{{ form.errors.email }}</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-clover-ink">Phone</label>
            <input v-model="form.phone" type="text" class="clover-input" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-clover-ink">Subject</label>
            <input v-model="form.subject" type="text" class="clover-input" />
        </div>
        <div>
            <label class="block text-sm font-semibold text-clover-ink">Message</label>
            <textarea v-model="form.message" rows="4" class="clover-input" required />
            <p v-if="form.errors.message" class="mt-1.5 text-sm text-red-600">{{ form.errors.message }}</p>
        </div>
        <button type="submit" class="clover-btn-primary w-full" :disabled="form.processing">
            Send message
        </button>
    </form>
</template>
