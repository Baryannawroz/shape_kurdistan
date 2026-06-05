<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: { type: String, default: '' },
    description: { type: String, default: '' },
    keywords: { type: String, default: '' },
    titleErrors: { type: String, default: '' },
    descriptionErrors: { type: String, default: '' },
    keywordsErrors: { type: String, default: '' },
});

const emit = defineEmits(['update:title', 'update:description', 'update:keywords']);

const titleModel = computed({
    get: () => props.title,
    set: (value) => emit('update:title', value),
});

const descriptionModel = computed({
    get: () => props.description,
    set: (value) => emit('update:description', value),
});

const keywordsModel = computed({
    get: () => props.keywords,
    set: (value) => emit('update:keywords', value),
});
</script>

<template>
    <div class="space-y-4 rounded-lg border border-amber-200/80 bg-amber-50/50 p-4">
        <p class="text-sm font-semibold text-amber-900">Google search (SEO)</p>
        <p class="text-xs text-amber-800/90">
            Use a clear title (about 50–60 characters) and description (about 140–160 characters) with words people search for.
        </p>
        <div>
            <label class="text-sm font-medium text-clover-ink">Search title</label>
            <input v-model="titleModel" type="text" maxlength="70" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
            <p class="mt-1 text-xs text-clover-muted">{{ titleModel.length }}/60 recommended</p>
            <p v-if="titleErrors" class="mt-1 text-sm text-red-600">{{ titleErrors }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-clover-ink">Search description</label>
            <textarea v-model="descriptionModel" rows="3" maxlength="320" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" />
            <p class="mt-1 text-xs text-clover-muted">{{ descriptionModel.length }}/160 recommended</p>
            <p v-if="descriptionErrors" class="mt-1 text-sm text-red-600">{{ descriptionErrors }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-clover-ink">Keywords (optional)</label>
            <input v-model="keywordsModel" type="text" class="mt-1 w-full rounded-lg border border-clover-border px-3 py-2 text-sm" placeholder="e.g. web design, Kurdistan, agency" />
            <p v-if="keywordsErrors" class="mt-1 text-sm text-red-600">{{ keywordsErrors }}</p>
        </div>
    </div>
</template>
